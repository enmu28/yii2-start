<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 8/2/14
 * Time: 11:20 AM
 */

namespace backend\controllers;

use app\models\example\Post;
use app\models\example\RedisPost;
use backend\jobs\JobExample;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii\web\Controller;
use Yii;

class QueueController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->queue->push(new JobExample([
            'ahihi' => 'Ahihi do ngoc ne! Queue job :))',
        ]));
//        return "Ahihi ddo ngoc!";
    }

//    public function actionSetredis(){
//        $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
//        $channel = $connection->channel();
//
//        $channel->queue_declare('nguyen_redis', false, false, false, false);
//        $callback = function ($msg) {
//            $data = json_decode($msg->body, true);
//            $container = Post::find()->where(['id' => $data['id'] ])->one();
//
//            $redis_container = new RedisContainer();
//            $redis_container->id = $container->id;
//            $redis_container->vendor = $container->id_vendor;
//            $redis_container->measurement_system = $container->id_measurement_system;
//            $redis_container->price = $container->price;
//            $redis_container->created_at = $container->created_at;
//            $redis_container->save();
//        };
//
//        $channel->basic_qos(null, 1, null);
//        $channel->basic_consume('nguyen_redis', '', false, true, false, false, $callback);
//
//        while(count($channel->callbacks)) {
//            $channel->wait();
//        }
//
//        $channel->close();
//        $connection->close();
//    }

    public function actionSetredis(){
        $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('nguyen_igusez', false, false, false, false);
        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            $post = Post::find()->where(['id' => $data['id'] ])->one();

            $redis_post = new RedisPost();
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
