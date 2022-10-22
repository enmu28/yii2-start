<?php
namespace app\models;

use yii\redis\ActiveRecord;

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

    public function rules(): array
    {
        return [
            [
                ['style_no', 'uom', 'prefix', 'sufix', 'height', 'width', 'length', 'upc', 'size_1', 'color_1', 'carton'], 'required',
                'message' => '{attribute} not value ',
            ],
            [
                ['style_no', 'uom', 'prefix', 'sufix', 'height', 'width', 'length', 'upc', 'size_1', 'color_1','size_2', 'color_2', 'size_3', 'color_3', 'carton'], 'integer',
                'message' => '{attribute} not integer ',
            ],
            [['style_no', 'uom', 'prefix', 'sufix', 'height', 'width', 'length', 'upc', 'size_1', 'color_1','size_2', 'color_2', 'size_3', 'color_3', 'carton'], 'compare', 'compareValue' => 0, 'operator' => '>', 'message' => 'value > 0!'],
//            [
//                ['style_no'], 'unique', 'message'=>'{attribute}:{value} already exists!',
//            ],
        ];
    }


}