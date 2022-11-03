<?php
namespace app\models\example;

use yii\redis\ActiveRecord;
use yii;

class RedisPost extends ActiveRecord
{
    public function attributes()
    {
        return[
            'id', 'title', 'content'
        ];
    }
}