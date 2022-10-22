<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii;

class Post extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->example;
    }

    public static function tableName(): string
    {
        return '{{%post}}';
    }
    public function attributes()
    {
        return[
            'id', 'title', 'content'
        ];
    }
}