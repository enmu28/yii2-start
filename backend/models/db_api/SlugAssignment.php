<?php

namespace app\models\db_api;

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

    public function getAction(){
        return $this->hasOne(SlugAction::className(), ['id' => 'slug_action_id']);
    }

    public function getSlugName(){
        return $this->hasOne(SlugName::className(), ['id' => 'slug_name_id']);
    }
}