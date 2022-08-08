<?php
namespace app\models\example;

use yii\db\ActiveRecord;
use yii;

class TblSystemNo extends yii\base\Model
{
    public $id, $id_container, $carton;
    public $style_no, $uom, $prefix;
    public $sufix, $height, $width;
    public $length, $upc;
    public $size_1, $size_2, $size_3;
    public $color_1, $color_2, $color_3;
    public static function getDb()
    {
        return Yii::$app->example;
    }

    public static function tableName(): string
    {
        return '{{%tbl_system_no}}';
    }
    public function attributes()
    {
        return[
            'id', 'id_container', 'carton',
            'style_no', 'uom', 'prefix',
            'sufix', 'height', 'width', 'length', 'upc',
            'size_1', 'size_2', 'size_3',
            'color_1', 'color_2', 'color_3'
        ];
    }

    public function attributeLabels()
    {
        return [
            'style_no' => 'Style',
            'uom' => 'Uom',
            'prefix' => 'Prefix',
            'sufix' => 'Sufix',
            'height' => 'Height',
            'width' => 'Width',
            'length' => 'Length',
            'upc' => 'Upc',
            'size_1' => 'Size',
            'color_1' => 'Color',
            'carton' => 'Carton'
        ];
    }

    public function rules(){
        return [
            [
                ['style_no', 'uom', 'prefix', 'sufix', 'height', 'width', 'length', 'upc', 'size_1', 'color_1', 'carton'], 'required',
                'message' => '{attribute} not value ',
            ],
        ];
    }
}