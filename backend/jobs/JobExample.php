<?php
namespace backend\jobs;

use yii\base\BaseObject;
use yii;
use yii\queue\JobInterface;
use yii\queue\RetryableJobInterface;

class JobExample extends BaseObject implements JobInterface{
    public $ahihi = 'Ahihi đồ ngốc!';

    public function execute($queue)
    {
        $k = 0;
        for($i=1; $i<=5; $i++){
            echo $i.'insert'.PHP_EOL;
            $result = Yii::$app->example->createCommand()->insert('ex_post',[
                'title' => $i,
                'content' => $i
            ])->execute();
            if($result){
                $k++;
            }
            echo 'Nguyen Hoan VI';
        }
        if($k == 5){
            echo $this->ahihi;
        }
    }
}