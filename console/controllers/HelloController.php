<?php

namespace console\controllers;

use app\models\Post;
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
        for($i=1; $i<=5; $i++){
//            $post = new Post();
//            $post->title = $i;
//            $post->content = $i;
//            $post->save();
            echo $i."\n";
        }
    }
}