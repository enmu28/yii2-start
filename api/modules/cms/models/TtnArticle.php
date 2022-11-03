<?php

namespace api\modules\cms\models;

use yii;

class TtnArticle extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db_api;
    }

    public function fields()
    {
        return [
            'id', 'category_id', 'type', 'name', 'title', 'slug', 'url', 'author', 'excerpt', 'content', 'page_number', 'status', 'published_at'
        ];
    }

    public static function tableName(): string
    {
        return '{{%article}}';
    }

    public function attributes()
    {
        return[
            'id', 'category_id', 'type', 'name', 'title', 'slug', 'url', 'author', 'excerpt', 'content', 'page_number', 'status', 'published_at'
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(TtnCategory::className(), ['id' => 'category_id']);
    }

    public function getResource(){
        return $this->hasMany(TtnResource::className(), ['news_article_id' => 'id']);
        // thieu where - news_article_typ ??
    }
}