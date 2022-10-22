<?php

namespace api\modules\example\models;

use yii;

class SlugName extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db_api;
    }

    public function fields()
    {
        return [
            'id','user_name', 'name', 'slug', 'created_at', 'updated_at'
        ];
    }

    public static function tableName(): string
    {
        return '{{%slug_name}}';
    }
    public function attributes()
    {
        return[
            'id', 'user_name', 'name', 'slug', 'created_at', 'updated_at'
        ];
    }

    public function getAssignment()
    {
        return $this->hasMany(SlugAssignment::className(), ['slug_name_id' => 'id']);
    }
}