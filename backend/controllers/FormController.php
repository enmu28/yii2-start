<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 8/2/14
 * Time: 11:20 AM
 */

namespace backend\controllers;

use app\models\example\Post;
use app\models\example\RedisContainer;
use app\models\example\RedisPost;
use app\models\example\RedisStyleNo;
use app\models\example\TblContainer;
use app\models\example\TblMeasurementSystem;
use app\models\example\TblStyleNo;
use app\models\example\TblVendor;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use Yii;

class FormController extends Controller
{
    public function actionIndex()
    {
//        $container = new TblContainer();
        $tbl_vendor = TblVendor::find()->all();
        $tbl_measurement_system = TblMeasurementSystem::find()->all();
        return $this->render('index', ['tbl_vendor' => $tbl_vendor, 'tbl_measurement_system' => $tbl_measurement_system]);
    }

    public function actionShow(){
        $query = RedisContainer::find()->orderBy([
            'created_at' => SORT_ASC,
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,

            'pagination' => [
                'pageSize' => 1,
            ],
        ]);
//        var_dump($dataProvider->totalCount); exit();
//if ($dataProvider->totalCount > 0) {;
        return $this->render('show', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {
        $request = Yii::$app->request;
        $redis_container = RedisContainer::find()->where(['id' => $request->get('id')])->with('styleno')->one();
        return render('view', ['redis_container'=> $redis_container]);
    }

    public function actionUpdate(){
        $tbl_vendor = TblVendor::find()->all();
        $tbl_measurement_system = TblMeasurementSystem::find()->all();

        $request = Yii::$app->request;
        $redis_container = RedisContainer::find()->where(['id' => $request->get('id')])->with('styleno')->one();
        $redis_style_no = new RedisStyleNo();

        $request_redis = Yii::$app->request->post();

        if(!empty($request_redis)){


            $container = TblContainer::find()->where(['id'=>$request->get('id')])->one();
            $container->id_vendor = $request_redis['redis_container_vendor'];
            $container->id_measurement_system = $request_redis['redis_container_system'];
            $container->price = $request_redis['RedisContainer']['price'];
            $container->created_at = $request_redis['redis_container_created_at'];
            $container->save();

            $redis_container->vendor =  $request_redis['redis_container_vendor'];
            $redis_container->measurement_system = $request_redis['redis_container_system'];
            $redis_container->price = $request_redis['RedisContainer']['price'];
            $redis_container->created_at = $request_redis['redis_container_created_at'];
            $redis_container->save();

            $count = count($redis_container->styleno);
            if($container->save() && $redis_container->save()){
                $check_count = 0;
                foreach($redis_container->styleno as $value){
//           style_no, uom, prefix, sufix, height, width, length, weight, upc, size_1, color_1, size_2, color_2, size_3, color_3,  carton
                    $style_no = TblStyleNo::find()->where(['id'=> $value->id])->one();
                    $style_no->style_no = $request_redis['RedisStyleNo']["style_no"][$value->id];
                    $style_no->uom = $request_redis['RedisStyleNo']["uom"][$value->id];
                    $style_no->prefix = $request_redis['RedisStyleNo']["prefix"][$value->id];
                    $style_no->sufix = $request_redis['RedisStyleNo']["sufix"][$value->id];
                    $style_no->height = $request_redis['RedisStyleNo']["height"][$value->id];
                    $style_no->width =$request_redis['RedisStyleNo']["width"][$value->id];
                    $style_no->length = $request_redis['RedisStyleNo']["length"][$value->id];
                    $style_no->weight = $request_redis['RedisStyleNo']["weight"][$value->id];
                    $style_no->upc = $request_redis['RedisStyleNo']["upc"][$value->id];
                    $style_no->size_1 = $request_redis['RedisStyleNo']["size_1"][$value->id];
                    $style_no->color_1 = $request_redis['RedisStyleNo']["color_1"][$value->id];
                    $style_no->size_2 = $request_redis['RedisStyleNo']["size_2"][$value->id];
                    $style_no->color_2 = $request_redis['RedisStyleNo']["color_2"][$value->id];
                    $style_no->size_3 = $request_redis['RedisStyleNo']["size_3"][$value->id];
                    $style_no->color_3 = $request_redis['RedisStyleNo']["color_3"][$value->id];
                    $style_no->carton = $request_redis['RedisStyleNo']["carton"][$value->id];
                    $style_no->save();


                    $style_no_redis = RedisStyleNo::find()->where(['id' => $value->id])->one();
                    $style_no_redis->style_no = $request_redis['RedisStyleNo']["style_no"][$value->id];
                    $style_no_redis->uom = $request_redis['RedisStyleNo']["uom"][$value->id];
                    $style_no_redis->prefix = $request_redis['RedisStyleNo']["prefix"][$value->id];
                    $style_no_redis->sufix = $request_redis['RedisStyleNo']["sufix"][$value->id];
                    $style_no_redis->height = $request_redis['RedisStyleNo']["height"][$value->id];
                    $style_no_redis->width =$request_redis['RedisStyleNo']["width"][$value->id];
                    $style_no_redis->length = $request_redis['RedisStyleNo']["length"][$value->id];
                    $style_no_redis->weight = $request_redis['RedisStyleNo']["weight"][$value->id];
                    $style_no_redis->upc = $request_redis['RedisStyleNo']["upc"][$value->id];
                    $style_no_redis->size_1 = $request_redis['RedisStyleNo']["size_1"][$value->id];
                    $style_no_redis->color_1 = $request_redis['RedisStyleNo']["color_1"][$value->id];
                    $style_no_redis->size_2 = $request_redis['RedisStyleNo']["size_2"][$value->id];
                    $style_no_redis->color_2 = $request_redis['RedisStyleNo']["color_2"][$value->id];
                    $style_no_redis->size_3 = $request_redis['RedisStyleNo']["size_3"][$value->id];
                    $style_no_redis->color_3 = $request_redis['RedisStyleNo']["color_3"][$value->id];
                    $style_no_redis->carton = $request_redis['RedisStyleNo']["carton"][$value->id];
                    $style_no_redis->save();

                    if($style_no->save() && $style_no_redis->save()){
                        $check_count += 1;
                    }
                }
                if($check_count == $count){
                    Yii::$app->session->setFlash('success', "Đã cập nhật thông tin thành công!.");
                }else{
                    Yii::$app->session->setFlash('success', "Đã cập nhật thông tin thất bại!.");
                }
            }
            $redis_container = RedisContainer::find()->where(['id' => $request->get('id')])->with('styleno')->one();

        }
        return render('update', [
            'redis_container'=> $redis_container,
            'redis_style_no' => $redis_style_no,
            'tbl_vendor' => $tbl_vendor,
            'tbl_measurement_system' => $tbl_measurement_system
        ]);
    }

    public function actionDelete(){
        $request = Yii::$app->request;
        $container = TblContainer::findOne($request->get('id'));
        $container->delete();
        $redis_container = RedisContainer::findOne($request->get('id'));
        $redis_container->delete();
        RedisStyleNo::deleteAll(['id_container'=> $request->get('id')]);
        return redirect("/form/show");
    }

    public function actionValidate()
    {
        $data = [];
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $container = TblContainer::findOne(Yii::$app->request->post()['id-container']);

            $data_queue = [];
            if(!$container){

                $data_queue['id'] = Yii::$app->request->post()['id-container'];
                $data_queue['id_vendor'] = Yii::$app->request->post()['id-vendor'];
                $data_queue['id_measurement_system'] = Yii::$app->request->post()['id-measurement-system'];
                $data_queue['price'] = Yii::$app->request->post()['price'];
                $data_queue['created_at'] = Yii::$app->request->post()['created-at'];
                $data_queue['style_no'] = [];

                $container = new TblContainer();
                $container->id = Yii::$app->request->post()['id-container'];
                $container->id_vendor = Yii::$app->request->post()['id-vendor'];
                $container->id_measurement_system = Yii::$app->request->post()['id-measurement-system'];
                $container->price = Yii::$app->request->post()['price'];
                $container->created_at = Yii::$app->request->post()['created-at'];
                $container->save();
//
//                $redis_container = new RedisContainer();
//                $redis_container->id = $container->id;
//                $redis_container->vendor = $container->id_vendor;
//                $redis_container->measurement_system = $container->id_measurement_system;
//                $redis_container->price = $container->price;
//                $redis_container->created_at = $container->created_at;
//                $redis_container->save();

                $check_count = 0;
                $count = count(Yii::$app->request->post()['style-no']['style-no']);
//                if($container->save() && $redis_container->save()){
                    for($i= 1; $i<=$count; $i++){
                        $data_queue['style_no'][$i] = [
                            'style_no' =>  Yii::$app->request->post()['style-no']['style-no'][$i],
                            'uom' => Yii::$app->request->post()['style-no']['uom'][$i],
                            'prefix' => Yii::$app->request->post()['style-no']['prefix'][$i],
                            'sufix' => Yii::$app->request->post()['style-no']['sufix'][$i],
                            'height' => Yii::$app->request->post()['style-no']['height'][$i],
                            'width' => Yii::$app->request->post()['style-no']['width'][$i],
                            'length' => Yii::$app->request->post()['style-no']['length'][$i],
                            'weight' => Yii::$app->request->post()['style-no']['weight'][$i],
                            'upc' => Yii::$app->request->post()['style-no']['upc'][$i],
                            'size_1' => Yii::$app->request->post()['style-no']['size_1'][$i],
                            'color_1' => Yii::$app->request->post()['style-no']['color_1'][$i],
                            'size_2' => Yii::$app->request->post()['style-no']['size_2'][$i],
                            'color_2' => Yii::$app->request->post()['style-no']['color_2'][$i],
                            'size_3' => Yii::$app->request->post()['style-no']['size_3'][$i],
                            'color_3' => Yii::$app->request->post()['style-no']['color_3'][$i],
                            'carton' => Yii::$app->request->post()['style-no']['carton'][$i]
                        ];
                        $check_count += 1;
                        $style_no = new TblStyleNo();
                        $style_no->id_container = $container->id;
                        $style_no->style_no = Yii::$app->request->post()['style-no']['style-no'][$i];
                        $style_no->uom = Yii::$app->request->post()['style-no']['uom'][$i];
                        $style_no->prefix = Yii::$app->request->post()['style-no']['prefix'][$i];
                        $style_no->sufix = Yii::$app->request->post()['style-no']['sufix'][$i];
                        $style_no->height =  Yii::$app->request->post()['style-no']['height'][$i];
                        $style_no->width = Yii::$app->request->post()['style-no']['width'][$i];
                        $style_no->length = Yii::$app->request->post()['style-no']['length'][$i];
                        $style_no->weight = Yii::$app->request->post()['style-no']['weight'][$i];
                        $style_no->upc = Yii::$app->request->post()['style-no']['upc'][$i];
                        $style_no->size_1 = Yii::$app->request->post()['style-no']['size_1'][$i];
                        $style_no->color_1= Yii::$app->request->post()['style-no']['color_1'][$i];
                        $style_no->size_2 = Yii::$app->request->post()['style-no']['size_2'][$i];
                        $style_no->color_2 = Yii::$app->request->post()['style-no']['color_2'][$i];
                        $style_no->size_3 = Yii::$app->request->post()['style-no']['size_3'][$i];
                        $style_no->color_3 = Yii::$app->request->post()['style-no']['color_3'][$i];
                        $style_no->carton = Yii::$app->request->post()['style-no']['carton'][$i];
                        $style_no->save();
//
//
//                        $redis_style_no = new RedisStyleNo();
//                        $redis_style_no->id = $style_no->id;
//                        $redis_style_no->id_container = $style_no->id_container;
//                        $redis_style_no->style_no = $style_no->style_no;
//                        $redis_style_no->uom = $style_no->uom;
//                        $redis_style_no->prefix = $style_no->prefix;
//                        $redis_style_no->sufix = $style_no->sufix;
//                        $redis_style_no->height = $style_no->height;
//                        $redis_style_no->width = $style_no->width;
//                        $redis_style_no->length = $style_no->length;
//                        $redis_style_no->weight = $style_no->weight;
//                        $redis_style_no->upc = $style_no->upc;
//                        $redis_style_no->size_1 = $style_no->size_1;
//                        $redis_style_no->color_1 = $style_no->color_1;
//                        $redis_style_no->size_2 = $style_no->size_2;
//                        $redis_style_no->color_2 = $style_no->color_2;
//                        $redis_style_no->size_3 = $style_no->size_3;
//                        $redis_style_no->color_3 = $style_no->color_3;
//                        $redis_style_no->carton = $style_no->carton;
//                        $redis_style_no->save();
//                        if($style_no->save() && $redis_style_no->save()){
//                            $check_count += 1;
//                        }
                    }
//                }

                if($check_count == $count){
//                    var_dump($data_queue); exit();

                    $data_queue = json_encode($data_queue);
                    $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
                    $channel = $connection->channel();
                    $channel->queue_declare('nguyen_igusez', false, false, false, false);

                    $msg = new AMQPMessage(
                        $data_queue,
                        array('delivery_mode' => 2)
                    );

                    $channel->basic_publish($msg, '', 'nguyen_igusez');
                    $channel->close();
                    $connection->close();

                    return  dataJson(true, null, 'Has sent to queuee!');
                }
//                else{
//                    TblContainer::deleteAll(['id' => Yii::$app->request->post()['id-container']]);
//                    RedisContainer::deleteAll(['id' => Yii::$app->request->post()['id-container']]);
//                    RedisStyleNo::deleteAll(['id_container'=> Yii::$app->request->post()['id-container']]);
//                    return  dataJson(false, null, 'Insert value error!');
//                }

            }else{
                return  dataJson('unique', null, 'Unique id container!');
            }
        }
    }

    public function actionDeleteStyleNo(){
        $request = Yii::$app->request;
        $id_container = $request->get('id_container');

        $style_no = TblStyleNo::findONe($request->get('id'));
        $style_no->delete();

        $redis_style_no = RedisStyleNo::findONe($request->get('id'));
        $redis_style_no->delete();

        return redirect("/form/update?id=$id_container");
    }

    public function actionRevice($queue_name){
        $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare($queue_name, false, false, false, false);

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };
        $channel->basic_consume($queue_name, '', false, false, false, false, $callback);
//
//       while ($channel->is_open()) {
//           $channel->wait();
//       }

        $channel->close();
        $connection->close();

    }

    public function actionRedisPost(){
        $post = new Post();
        $post->title = 'abc';
        $post->content = 'abc';
        $post->save();

        $data_queue = [];
        $data_queue['id'] = $post->id;
        $data_queue = json_encode($data_queue);
        $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('nguyen_igusez', false, false, false, false);

        $msg = new AMQPMessage(
            $data_queue,
            array('delivery_mode' => 2)
        );

        $channel->basic_publish($msg, '', 'nguyen_igusez');
        $channel->close();
        $connection->close();

        return  dataJson(true, null, 'Has sent to eee!');

    }

}
