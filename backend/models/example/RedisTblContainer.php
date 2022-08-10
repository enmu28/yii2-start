<?php
namespace app\models\example;

use yii\redis\ActiveRecord;
use yii;

class RedisTblContainer extends ActiveRecord
{
    public function attributes()
    {
        return[
            'id',
            'id_vendor', 'id_measurement_system',
            'price', 'created_at'
        ];
    }
}