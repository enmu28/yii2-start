<?php
namespace backend\jobs;

use app\models\example\Post;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class JobExample extends BaseObject implements JobInterface{
    public $title;
    public $content;

    public function execute($queue)
    {
        for($i=1; $i<=100; $i++){
            echo $i.'insert'.PHP_EOL;
            $post = new Post();
            $post->title = 'abc';
            $post->content = 'abc';
            $post->save();
        }
    }
}