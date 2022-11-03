<?php

namespace api\modules\cms\models;

use yii;

class TtnNewsArtResourceLog extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db_api;
    }

    public function fields()
    {
        return [
            'id', 'news_art_resource_id', 'news_art_resource_type', 'action', 'by_user', 'created_at', 'updated_at'
        ];
    }

    public static function tableName(): string
    {
        return '{{%news_art_resource_log}}';
    }

    public function attributes()
    {
        return[
            'id', 'news_art_resource_id', 'news_art_resource_type', 'action', 'by_user', 'created_at', 'updated_at'
        ];
    }
}