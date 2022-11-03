<?php
namespace api\modules\cms\models;

use yii\db\ActiveRecord;
use yii;

class Container extends ActiveRecord implements yii\filters\RateLimitInterface
{
    public $rateLimit = 1;
    public $allowance;
    public $allowance_updated_at;

    public function getRateLimit($request, $action) {
        return [$this->rateLimit,5];
    }

    public function loadAllowance($request, $action)
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->save();
    }

    public function fields()
    {
        return [
            'id', 'id_vendor', 'id_measurement_system', 'price', 'created_at',
            'abcd' => function(){
//                $a = Vendor::find()->where(['id'=> $this->id_vendor])->one();
                return $this->getVendor()->name;
            }
        ];
    }
    public static function getDb()
    {
        return Yii::$app->example;
    }

    public static function tableName(): string
    {
        return '{{%tbl_container}}';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_vendor' => 'Vendor',
            'id_measurement_system' => 'System',
            'price' => 'Price',
            'created_at' => 'Date'
        ];
    }
    public function attributes()
    {
        return[
            'id',
            'id_vendor', 'id_measurement_system',
            'price', 'created_at'
        ];
    }

    public function rules()
    {
        return [
            [
                ['id', 'id_vendor', 'id_measurement_system', 'price', 'created_at'], 'required',
                'message' => '{attribute} not value ',
            ],
            [['id_vendor', 'id_measurement_system', 'price'], 'integer'],
            [
                ['id'], 'unique', 'message'=>'{attribute}:{value} already exists!',
            ],
        ];
    }

//
    public function getVendor(){
        return $this->hasOne(Vendor::className(), ['id' => 'id_vendor'])->select('name')->one();
    }
}