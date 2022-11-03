<?php

namespace console\controllers;

use app\models\example\TblContainer;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use yii\web\Controller;

class RbQueueController extends Controller
{
    public function actionHello(){
        $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();


        $count = $channel->queue_declare('nguyen_igusez', false, false, false, false);
        for($i = 1; $i<=$count[1]; $i++){
            $data = ($channel->basic_get('nguyen_igusez',  true, null)->body);
            $data = json_decode($data, true);
            $container = new TblContainer();
            $container->id = $data['id'];
            $container->id_vendor = $data['id_vendor'];
            $container->id_measurement_system = $data['id_measurement_system'];
            $container->price = $data['price'];
            $container->created_at = $data['created_at'];
            $container->save();
        }


        $channel->close();
        $connection->close();
    }

    public function actionHi(){
        $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $count = $channel->queue_declare('nguyen_igusez', false, false, false, false);
        for($i = 1; $i<=$count[1]; $i++){
            $data = ($channel->basic_get('nguyen_igusez',  true, null)->body);
            $data = json_decode($data, true);
            $container = new TblContainer();
            $container->id = $data['id'];
            $container->id_vendor = $data['id_vendor'];
            $container->id_measurement_system = $data['id_measurement_system'];
            $container->price = $data['price'];
            $container->created_at = $data['created_at'];
            $container->save();
        }


        $channel->close();
        $connection->close();
    }
}