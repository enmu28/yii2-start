<?php
namespace app\models\example;

use yii\redis\ActiveRecord;
use yii;

class RedisContainer extends ActiveRecord
{
    public function attributes()
    {
        return['id', 'vendor', 'measurement_system', 'price', 'created_at'];
    }


    public function getStyleno()
    {
        return $this->hasMany(RedisStyleNo::className(), ['id_container' => 'id']);
    }

    public function rules()
    {
        return [
            [
                ['price'], 'required',
                'message' => '{attribute} not value ',
            ],
            [['price'], 'integer'],
            ['price', 'compare', 'compareValue' => 0, 'operator' => '>', 'message' => 'Price must be great than 0!'],
        ];
    }

//    public function getVendor(){
//        return $this->hasOne(TblVendor::className(), ['id' => 'ivendor']);
//    }
//
//    public function getMeasurementsystem(){
//        return $this->hasOne(TblMeasurementSystem::className(), ['id' => 'id']);
//    }
}