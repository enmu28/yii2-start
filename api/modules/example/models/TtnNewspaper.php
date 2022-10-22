<?php

namespace api\modules\example\models;

use yii;

class TtnNewspaper extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db_api;
    }

    public function fields()
    {
        return [
            'id', 'category_id','type', 'color', 'author', 'page_number', 'status', 'published_at',
        ];
    }

    public static function tableName(): string
    {
        return '{{%newspaper}}';
    }

    public function attributes()
    {
        return[
            'id', 'category_id','type', 'color', 'author', 'page_number', 'status', 'published_at',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(TtnCategory::className(), ['id' => 'category_id']);
    }

    public function getResource(){
        return $this->hasOne(TtnResource::className(), ['news_article_id' => 'id']);
    }
}