<?php

namespace console\controllers;

use app\models\example\Post;
use app\models\example\RedisPost;
use app\models\RedisContainer;
use app\models\RedisStyleNo;
use app\models\TblContainer;
use app\models\TblStyleNo;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\Inflector;
use yii\console\ExitCode;
use Faker\Factory;
use common\models\Article;
use common\models\ArticleCategory;
use common\models\User;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class AppController extends Controller
{
    /** @var array */
    public $writablePaths = [
        '@common/runtime',
        '@frontend/runtime',
        '@frontend/web/assets',
        '@backend/runtime',
        '@backend/web/assets',
        '@storage/cache',
        '@storage/web/source',
        '@api/runtime',
    ];

    /** @var array */
    public $executablePaths = [
        '@backend/yii',
        '@frontend/yii',
        '@console/yii',
        '@api/yii',
    ];

    /** @var array */
    public $generateKeysPaths = [
        '@base/.env'
    ];

    /**
     * Sets given keys to .env file
     */
    public function actionSetKeys()
    {
        $this->setKeys($this->generateKeysPaths);
    }

    /**
     * @throws \yii\base\InvalidRouteException
     * @throws \yii\console\Exception
     */
    public function actionSetup()
    {
        $this->runAction('set-writable', ['interactive' => $this->interactive]);
        $this->runAction('set-executable', ['interactive' => $this->interactive]);
        $this->runAction('set-keys', ['interactive' => $this->interactive]);
        \Yii::$app->runAction('migrate/up', ['interactive' => $this->interactive]);
        \Yii::$app->runAction('rbac-migrate/up', ['interactive' => $this->interactive]);
    }

    /**
     * Truncates all tables in the database.
     * @throws \yii\db\Exception
     */
    public function actionTruncate()
    {
        $dbName = Yii::$app->db->createCommand('SELECT DATABASE()')->queryScalar();
        if ($this->confirm('This will truncate all tables of current database [' . $dbName . '].')) {
            Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
            $tables = Yii::$app->db->schema->getTableNames();
            foreach ($tables as $table) {
                $this->stdout('Truncating table ' . $table . PHP_EOL, Console::FG_RED);
                Yii::$app->db->createCommand()->truncateTable($table)->execute();
            }
            Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();
        }
    }

    /**
     * Drops all tables in the database.
     * @throws \yii\db\Exception
     */
    public function actionDrop()
    {
        $dbName = Yii::$app->db->createCommand('SELECT DATABASE()')->queryScalar();
        if ($this->confirm('This will drop all tables of current database [' . $dbName . '].')) {
            Yii::$app->db->createCommand("SET foreign_key_checks = 0")->execute();
            $tables = Yii::$app->db->schema->getTableNames();
            foreach ($tables as $table) {
                $this->stdout('Dropping table ' . $table . PHP_EOL, Console::FG_RED);
                Yii::$app->db->createCommand()->dropTable($table)->execute();
            }
            Yii::$app->db->createCommand("SET foreign_key_checks = 1")->execute();
        }
    }

    /**
     * @param string $charset
     * @param string $collation
     * @throws \yii\base\ExitException
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\Exception
     */
    public function actionAlterCharset($charset = 'utf8mb4', $collation = 'utf8mb4_unicode_ci')
    {
        if (Yii::$app->db->getDriverName() !== 'mysql') {
            Console::error('Only mysql is supported');
            Yii::$app->end(1);
        }

        if (!$this->confirm("Convert tables to character set {$charset}?")) {
            Yii::$app->end();
        }

        $tables = Yii::$app->db->getSchema()->getTableNames();
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
        foreach ($tables as $table) {
            $command = Yii::$app->db->createCommand("ALTER TABLE {$table} CONVERT TO CHARACTER SET :charset COLLATE :collation")->bindValues([
                ':charset' => $charset,
                ':collation' => $collation
            ]);
            $command->execute();
        }
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
        Console::output('All ok!');
    }


    /**
     * Adds write permissions
     */
    public function actionSetWritable()
    {
        $this->setWritable($this->writablePaths);
    }

    /**
     * Adds execute permissions
     */
    public function actionSetExecutable()
    {
        $this->setExecutable($this->executablePaths);
    }

    /**
     * Adds random data useful for the frontend application.
     *
     * @param integer $count the amount of random data to be generated
     * @return void
     */
    public function actionDemoData($count = 10)
    {
        // get faker
        if (!class_exists(Factory::class)) {
            Console::output('Faker should be installed. Run `composer install --dev`');
            return ExitCode::CONFIG;
        }

        // add articles and categories
        $factory = Factory::create();
        $this->addArticleCategories($count, $factory);
        $this->addArticles($count, $factory);

        return ExitCode::OK;
    }

    /**
     * Creates random ArticleCategory models.
     *
     * @param integer $count The amount of models to be generated
     * @param Faker\Factory $factory The faker factory object
     * @return void
     */
    private function addArticleCategories($count, $factory)
    {
        for ($i=0; $i < $count; $i++) {
            $addParent = rand(0, 2) > 1;
            $parent_id = null;
            if ($addParent) {
                $categories = ArticleCategory::find()->all();
                $parent_id = $categories[array_rand($categories)]->id;
            }

            $category = new ArticleCategory([
                'title' => $factory->word.' '.$factory->word,
                'status' => array_rand(ArticleCategory::statuses()),
                'parent_id' => $parent_id
            ]);
            $category->slug = Inflector::slug($category->title);
            $category->save(false);
        }
    }

    /**
     * Creates random Article models.
     *
     * @param integer $count The amount of models to be generated
     * @param Faker\Factory $factory The faker factory object
     * @return void
     */
    private function addArticles($count, $factory)
    {
        // get all users and categories
        $users = User::find()->all();
        $categories = ArticleCategory::find()->all();

        if (count($users) === 0) {
            Console::output('No users found');
            return ExitCode::CONFIG;
        }

        for ($i=0; $i < $count; $i++) {
            $postUser = $users[array_rand($users)];
            $category = $categories[array_rand($categories)];
            $factory-
            $article = new Article([
                'category_id' => $category->id,
                'title' => $factory->text(64),
                'body' => $factory->realText(rand(1000, 4000)),
                'created_by' => $postUser->id,
                'updated_by' => $postUser->id,
                'published_at' => rand(time(), strtotime('-2 years')),
                'created_at' => time(),
                'updated_at' => time(),
                'status' => array_rand(Article::statuses())
            ]);
            $article->detachBehaviors();
            $article->slug = Inflector::slug($article->title);
            $article->save(false);
        }
    }

    /**
     * @param $paths
     */
    private function setWritable($paths)
    {
        foreach ($paths as $writable) {
            $writable = Yii::getAlias($writable);
            Console::output("Setting writable: {$writable}");
            @chmod($writable, 0777);
        }
    }

    /**
     * @param $paths
     */
    private function setExecutable($paths)
    {
        foreach ($paths as $executable) {
            $executable = Yii::getAlias($executable);
            Console::output("Setting executable: {$executable}");
            @chmod($executable, 0755);
        }
    }

    /**
     * @param $paths
     */
    private function setKeys($paths)
    {
        foreach ($paths as $file) {
            $file = Yii::getAlias($file);
            Console::output("Generating keys in {$file}");
            $content = file_get_contents($file);
            $content = preg_replace_callback('/<generated_key>/', function () {
                $length = 32;
                $bytes = openssl_random_pseudo_bytes(32, $cryptoStrong);
                return strtr(substr(base64_encode($bytes), 0, $length), '+/', '_-');
            }, $content);
            file_put_contents($file, $content);
        }
    }

    public function actionHello(){
        $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('nguyen_igusez', false, false, false, false);
        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            $container = new TblContainer();
            $container->id = $data['id'];
            $container->id_vendor = $data['id_vendor'];
            $container->id_measurement_system = $data['id_measurement_system'];
            $container->price = $data['price'];
            $container->created_at = $data['created_at'];
            $container->save();


            $data_redis = [];
            if($container->save()){
                $data_redis['id'] = $container->id;
            }

//            $redis_container = new RedisContainer();
//            $redis_container->id = $container->id;
//            $redis_container->vendor = $container->id_vendor;
//            $redis_container->measurement_system = $container->id_measurement_system;
//            $redis_container->price = $container->price;
//            $redis_container->created_at = $container->created_at;
//            $redis_container->save();

            if($container->save()){
                $count_style_no = count($data['style_no']);
                for($i=1; $i<=$count_style_no; $i++){
                    $style_no = new TblStyleNo();
                    $style_no->id_container = $container->id;
                    $style_no->style_no = $data['style_no'][$i]['style_no'];
                    $style_no->uom = $data['style_no'][$i]['uom'];
                    $style_no->prefix = $data['style_no'][$i]['prefix'];
                    $style_no->sufix = $data['style_no'][$i]['sufix'];
                    $style_no->height =  $data['style_no'][$i]['height'];
                    $style_no->width = $data['style_no'][$i]['width'];
                    $style_no->length = $data['style_no'][$i]['length'];
                    $style_no->weight = $data['style_no'][$i]['weight'];
                    $style_no->upc = $data['style_no'][$i]['upc'];
                    $style_no->size_1 = $data['style_no'][$i]['size_1'];
                    $style_no->color_1= $data['style_no'][$i]['color_1'];
                    $style_no->size_2 = $data['style_no'][$i]['size_2'];
                    $style_no->color_2 = $data['style_no'][$i]['color_2'];
                    $style_no->size_3 = $data['style_no'][$i]['size_3'];
                    $style_no->color_3 = $data['style_no'][$i]['color_3'];
                    $style_no->carton = $data['style_no'][$i]['carton'];
                    $style_no->save();

                    if($style_no->save()){
                        $data_redis['style_no'][$i] = $style_no->id;
                    }
//
//                    $redis_style_no = new RedisStyleNo();
//                    $redis_style_no->id = $style_no->id;
//                    $redis_style_no->id_container = $style_no->id_container;
//                    $redis_style_no->style_no = $style_no->style_no;
//                    $redis_style_no->uom = $style_no->uom;
//                    $redis_style_no->prefix = $style_no->prefix;
//                    $redis_style_no->sufix = $style_no->sufix;
//                    $redis_style_no->height = $style_no->height;
//                    $redis_style_no->width = $style_no->width;
//                    $redis_style_no->length = $style_no->length;
//                    $redis_style_no->weight = $style_no->weight;
//                    $redis_style_no->upc = $style_no->upc;
//                    $redis_style_no->size_1 = $style_no->size_1;
//                    $redis_style_no->color_1 = $style_no->color_1;
//                    $redis_style_no->size_2 = $style_no->size_2;
//                    $redis_style_no->color_2 = $style_no->color_2;
//                    $redis_style_no->size_3 = $style_no->size_3;
//                    $redis_style_no->color_3 = $style_no->color_3;
//                    $redis_style_no->carton = $style_no->carton;
//                    $redis_style_no->save();
                }
            }
            $data_redis = json_encode($data_redis);
            $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
            $channel = $connection->channel();
            $channel->queue_declare('nguyen_redis', false, false, false, false);
            $msg = new AMQPMessage(
                $data_redis,
                array('delivery_mode' => 2)
            );
            $channel->basic_publish($msg, '', 'nguyen_redis');
            $channel->close();
            $connection->close();
        };




        $channel->basic_qos(null, 1, null);
        $channel->basic_consume('nguyen_igusez', '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }


    public function actionSetredis(){
        $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('nguyen_redis', false, false, false, false);
        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            $container = TblContainer::find()->where(['id' => $data['id'] ])->one();

            $redis_container = new RedisContainer();
            $redis_container->id = $container->id;
            $redis_container->vendor = $container->id_vendor;
            $redis_container->measurement_system = $container->id_measurement_system;
            $redis_container->price = $container->price;
            $redis_container->created_at = $container->created_at;
            $redis_container->save();

            if($redis_container->save()){
                $count_style_no = count($data['style_no']);
//                echo $count_style_no."\n";
                for($i=1; $i<=$count_style_no; $i++){
//                    echo $data['style_no'][$i]."\n";
                    $style_no = null;
                    $style_no = TblStyleNo::find()->where([ 'id' => $data['style_no'][$i] ])->one();

                    $redis_style_no = new RedisStyleNo();
                    $redis_style_no->id = $style_no->id;
                    $redis_style_no->id_container = $style_no->id_container;
                    $redis_style_no->style_no = $style_no->style_no;
                    $redis_style_no->uom = $style_no->uom;
                    $redis_style_no->prefix = $style_no->prefix;
                    $redis_style_no->sufix = $style_no->sufix;
                    $redis_style_no->height = $style_no->height;
                    $redis_style_no->width = $style_no->width;
                    $redis_style_no->length = $style_no->length;
                    $redis_style_no->weight = $style_no->weight;
                    $redis_style_no->upc = $style_no->upc;
                    $redis_style_no->size_1 = $style_no->size_1;
                    $redis_style_no->color_1 = $style_no->color_1;
                    $redis_style_no->size_2 = $style_no->size_2;
                    $redis_style_no->color_2 = $style_no->color_2;
                    $redis_style_no->size_3 = $style_no->size_3;
                    $redis_style_no->color_3 = $style_no->color_3;
                    $redis_style_no->carton = $style_no->carton;
                    $redis_style_no->save();
                }
            }
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume('nguyen_redis', '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    public function actionSetredispost(){
        $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('nguyen_igusez', false, false, false, false);
        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            $post = \console\models\Post::find()->where(['id' => $data['id'] ])->one();

            $redis_post = new \console\models\RedisPost();
            $redis_post->id = $post->id;
            $redis_post->title = $post->title;
            $redis_post->content = $post->content;
            $redis_post->save();
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume('nguyen_igusez', '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}

