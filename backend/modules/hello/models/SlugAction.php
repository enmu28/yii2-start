<?php

namespace backend\modules\hello\models;

use yii;

class SlugAction extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db_api;
    }

    public function fields()
    {
        return [
            'id', 'name', 'action_controller', 'action_name', 'created_at', 'updated_at'
        ];
    }

    public static function tableName(): string
    {
        return '{{%slug_action}}';
    }
    public function attributes()
    {
        return[
            'id','name', 'action_controller', 'action_name', 'created_at', 'updated_at'
        ];
    }
}