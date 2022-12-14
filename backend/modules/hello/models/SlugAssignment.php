<?php

namespace backend\modules\hello\models;

use yii;

class SlugAssignment extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db_api;
    }

    public function fields()
    {
        return [
            'id', 'slug_name_id', 'slug_action_id'
        ];
    }

    public static function tableName(): string
    {
        return '{{%slug_assignment}}';
    }

    public function attributes()
    {
        return[
           'id', 'slug_name_id', 'slug_action_id'
        ];
    }
}