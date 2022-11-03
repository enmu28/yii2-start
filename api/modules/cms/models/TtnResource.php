<?php

namespace api\modules\cms\models;

use yii;

class TtnResource extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db_api;
    }

    public function fields()
    {
        return [
            'id', 'news_article_id', 'news_article_type', 'thumbnail', 'type', 'name', 'title', 'path', 'extension', 'status', 'created_at', 'updated_at'
        ];
    }

    public static function tableName(): string
    {
        return '{{%resource}}';
    }

    public function attributes()
    {
        return[
            'id', 'news_article_id', 'news_article_type', 'thumbnail', 'type', 'name', 'title', 'path', 'extension', 'status', 'created_at', 'updated_at'
        ];
    }

    public function getMap(){
        return $this->hasMany(TtnMap::class, ['resource_id' => 'id']);
    }
}