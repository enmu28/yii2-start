<?php
namespace console\models;

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