<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii;

class TblStyleNo extends ActiveRecord
{
//    public $id, $id_container, $carton;
//    public $style_no, $uom, $prefix;
//    public $sufix, $height, $width;
//    public $length, $upc;
//    public $size_1, $size_2, $size_3;
//    public $color_1, $color_2, $color_3;
    public static function getDb()
    {
        return Yii::$app->example;
    }

    public static function tableName(): string
    {
        return '{{%tbl_style_no}}';
    }
    public function attributeLabels()
    {
        return [
            'id_container' => 'ID container',
            'style_no' => 'Style',
            'uom' => 'Uom',
            'prefix' => 'Prefix',
            'sufix' => 'Sufix',
            'height' => 'Height',
            'width' => 'Width',
            'length' => 'Length',
            'weight' => 'Weight',
            'upc' => 'Upc',
            'size_1' => 'Size',
            'color_1' => 'Color',
            'carton' => 'Carton'
        ];
    }
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

//    public function rules(): array
//    {
//        return [
//            [
//                ['style_no', 'uom', 'prefix', 'sufix', 'height', 'width', 'length', 'upc', 'size_1', 'color_1', 'carton'], 'required',
//                'message' => '{attribute} not value ',
//            ],
//            [
//                ['style_no', 'uom', 'prefix', 'sufix', 'height', 'width', 'length', 'upc', 'size_1', 'color_1', 'carton'], 'integer',
//                'message' => '{attribute} not integer ',
//            ],
//            [
//                ['style_no'], 'unique', 'message'=>'{attribute}:{value} already exists!',
//            ],
//            ['style_no', 'each', 'rule' => ['required']]
//        ];
//    }
}