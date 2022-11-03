<?php

namespace console\controllers;

use api\modules\cms\constants\ExampleConstant;
use api\modules\cms\helpers\ArticleHelper;
use api\modules\cms\helpers\NewspaperHelper;
use api\modules\cms\helpers\ResourceHelper;
use api\modules\cms\models\TtnResource;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use yii\console\Controller;

class CmsController extends Controller
{
    public function isConnect(){
        return new AMQPStreamConnection('docker_rabbitmq', 5672, 'guest', 'guest');
    }

    public function actionCreateElasticResource(){
        $connection = $this->isConnect();
        $channel = $connection->channel();

        $channel->queue_declare(ExampleConstant::CREATE_ELASTIC_RESOURCE, false, false, false, false);
        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            for($i = 0; $i < count($data); $i ++){
                ResourceHelper::createElasticResource($data[$i]);
            }
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume(ExampleConstant::CREATE_ELASTIC_RESOURCE, '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    public function actionCreateElasticNewspaper(){
        $connection = $this->isConnect();
        $channel = $connection->channel();

        $channel->queue_declare(ExampleConstant::CREATE_ELASTIC_NEWSPAPER, false, false, false, false);
        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            NewspaperHelper::createElasticNewspaper($data['id_newspaper']);
            ResourceHelper::createElasticResource($data['id_resource']);
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume(ExampleConstant::CREATE_ELASTIC_NEWSPAPER, '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    public function actionCreateElasticArticle(){
        $connection = $this->isConnect();
        $channel = $connection->channel();

        $channel->queue_declare(ExampleConstant::CREATE_ELASTIC_ARTICLE, false, false, false, false);
        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            ArticleHelper::createElasticArt($data['id_art']);
            ResourceHelper::createElasticResource($data['id_resource']);
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume(ExampleConstant::CREATE_ELASTIC_ARTICLE, '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}