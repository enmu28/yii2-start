<?php
namespace app\models\example;

use yii\redis\ActiveRecord;
use yii;

class RedisStyleNo extends ActiveRecord
{
    public function attributes()
    {
        return[
            'id', 'id_container', 'carton',
            'style_no', 'uom', 'prefix',
            'sufix', 'height', 'width', 'length', 'weight','upc',
            'size_1', 'size_2', 'size_3',
            'color_1', 'color_2', 'color_3'
        ];
    }


}