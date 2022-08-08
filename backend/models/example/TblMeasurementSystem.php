<?php
namespace app\models\example;

use yii\base\Model;
use yii\db\ActiveRecord;
use yii;

class TblMeasurementSystem extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->example;
    }

    public static function tableName(): string
    {
        return '{{%tbl_measurement_system}}';
    }
    public function attributes()
    {
        return['id', 'name'];
    }
}