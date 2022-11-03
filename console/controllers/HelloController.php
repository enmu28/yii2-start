<?php

namespace console\controllers;

use app\models\RedisContainer;
use app\models\RedisStyleNo;
use app\models\TblContainer;
use app\models\TblStyleNo;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use yii\console\Controller;

class HelloController extends Controller
{
    public function actionIndex()
    {
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

            $redis_container = new RedisContainer();
            $redis_container->id = $container->id;
            $redis_container->vendor = $container->id_vendor;
            $redis_container->measurement_system = $container->id_measurement_system;
            $redis_container->price = $container->price;
            $redis_container->created_at = $container->created_at;
            $redis_container->save();

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
//
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
        $channel->basic_consume('nguyen_igusez', '', false, false, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    public function actionRedis(){
        $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('nguyen_igusez', false, false, false, false);
    }
}