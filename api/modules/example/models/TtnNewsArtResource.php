<?php

namespace api\modules\example\models;

use yii;

class TtnNewsArtResource extends \yii\db\ActiveRecord
{
    public function fields()
    {
        return [
            'id', 'news_art_id', 'news_art_type', 'resource_id'
        ];
    }

    public static function tableName(): string
    {
        return '{{%news_art_resource}}';
    }

    public function attributes()
    {
        return[
            'id', 'news_art_id', 'news_art_type', 'resource_id'
        ];
    }
}