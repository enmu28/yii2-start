<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii;

class TblVendor extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->example;
    }

    public static function tableName(): string
    {
        return '{{%tbl_vendor}}';
    }
    public function attributes()
    {
        return[
            'id', 'name'
        ];
    }
}