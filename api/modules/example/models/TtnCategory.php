<?php

namespace api\modules\example\models;

use yii;

class TtnCategory extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db_api;
    }

    public function fields()
    {
        return [
            'id', 'name', 'created_at', 'updated_at'
        ];
    }

    public static function tableName(): string
    {
        return '{{%category}}';
    }
    public function attributes()
    {
        return[
            'id', 'name', 'created_at', 'updated_at'
        ];
    }
}