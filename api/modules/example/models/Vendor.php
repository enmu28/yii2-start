<?php

namespace api\modules\example\models;

use yii;

class Vendor extends \yii\db\ActiveRecord implements yii\filters\RateLimitInterface
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
            'id', 'name', 'abcf' => function(){
                return 1;
            }
        ];
    }
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