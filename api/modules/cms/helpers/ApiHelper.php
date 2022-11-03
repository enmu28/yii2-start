<?php

namespace api\modules\cms\helpers;

use api\modules\cms\models\SlugAction;
use api\modules\cms\models\TtnNewsArtResourceLog;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;

class ApiHelper
{
    public static function getUserName(){
        $publicKey = file_get_contents("../web/text/public_key");
        $request = \Yii::$app->request;
        $token = $request->getHeaders()['authorization'];
        $jwt = substr($token,7);
        $decoded = JWT::decode($jwt, new Key($publicKey, 'RS512'));
        return $decoded->full_name;
    }

    public static function validateInput($array, $method){
        $message_error = null;

        if($method == 'post'){
            $request = \Yii::$app->request->post();
            if(q()->isGet){
                $message_error = "Không đúng phương thức";
            }
        }else{
            $request = \Yii::$app->request->get();
            if(q()->isPost){
                $message_error = "Không đúng phương thức";
            }
        }

        if($message_error == null){
            foreach ($array as $key => $value){
                if(empty($request[$value])){
                    $message_error[]= "Thiếu tham số $".$value;
                }
            }
        }
        return $message_error;
    }

    public static function setLogAction($news_art_resource_id, $news_art_resource_type, $type){
        $action_controller = Yii::$app->controller->id;
        $action_name = $type;

        $log_action = SlugAction::find()->where(['action_controller' => $action_controller])
            ->andWhere(['action_name' => $action_name])->one();

        $news_art_resource_log = new TtnNewsArtResourceLog();
        $news_art_resource_log->news_art_resource_id = $news_art_resource_id;
        $news_art_resource_log->news_art_resource_type = $news_art_resource_type;
        $news_art_resource_log->action = $log_action->name;
        $news_art_resource_log->by_user = self::getUserName();
        $news_art_resource_log->created_at = strtotime(date('Ymd his'));
        $result = $news_art_resource_log->save();

        if(!empty($result)){
            return true;
        }else{
            return false;
        }
    }

    public static function sentQueue($data, $queue_name){
        $data_queue = json_encode($data);
        $connection = new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare($queue_name, false, false, false, false);

        $msg = new AMQPMessage(
            $data_queue,
            array('delivery_mode' => 2)
        );

        $channel->basic_publish($msg, '', $queue_name);
        $channel->close();
        $connection->close();
    }
}