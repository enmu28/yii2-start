<?php

namespace api\modules\cms\models;

use yii;

class TtnMap extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->db_api;
    }

    public function fields()
    {
        return [
            'id', 'resource_id', 'target', 'url', 'title', 'shape', 'html_map', 'html_svg'
        ];
    }

    public static function tableName(): string
    {
        return '{{%map}}';
    }

    public function attributes()
    {
        return[
            'id', 'resource_id', 'target', 'url', 'title', 'shape', 'html_map', 'html_svg'
        ];
    }
}