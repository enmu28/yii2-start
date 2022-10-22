<?php
///**
// * @link https://www.yiiframework.com/
// * @copyright Copyright (c) 2008 Yii Software LLC
// * @license https://www.yiiframework.com/license/
// */
//
//namespace api\modules\example\controllers;
//
//use backend\modules\hello\helpers\FuckuHelper;
//use backend\modules\hello\models\ElasticSlug;
//use Firebase\JWT\JWT;
//use Firebase\JWT\Key;
//use yii\base\InvalidConfigException;
//use yii\base\Model;
//use yii\db\Exception;
//use yii\rest\Controller;
//use Yii;
//
//class ActiveController extends Controller
//{
//    public $modelClass;
//
//    public $updateScenario = Model::SCENARIO_DEFAULT;
//
//    public $createScenario = Model::SCENARIO_DEFAULT;
//
//    public function init()
//    {
//        parent::init();
//        if ($this->modelClass === null) {
//            throw new InvalidConfigException('The "modelClass" property must be set.');
//        }
//    }
//
//    public function actions()
//    {
//        return [
//            'index' => [
//                'class' => 'yii\rest\IndexAction',
//                'modelClass' => $this->modelClass,
//                'checkAccess' => [$this, 'checkAccess'],
//            ],
//            'view' => [
//                'class' => 'yii\rest\ViewAction',
//                'modelClass' => $this->modelClass,
//                'checkAccess' => [$this, 'checkAccess'],
//            ],
//            'create' => [
//                'class' => 'yii\rest\CreateAction',
//                'modelClass' => $this->modelClass,
//                'checkAccess' => [$this, 'checkAccess'],
//                'scenario' => $this->createScenario,
//            ],
//            'update' => [
//                'class' => 'yii\rest\UpdateAction',
//                'modelClass' => $this->modelClass,
//                'checkAccess' => [$this, 'checkAccess'],
//                'scenario' => $this->updateScenario,
//            ],
//            'delete' => [
//                'class' => 'yii\rest\DeleteAction',
//                'modelClass' => $this->modelClass,
//                'checkAccess' => [$this, 'checkAccess'],
//            ],
//            'options' => [
//                'class' => 'yii\rest\OptionsAction',
//            ],
//        ];
//    }
//
//    protected function verbs()
//    {
//        return [
//            'index' => ['GET', 'HEAD'],
//            'view' => ['GET', 'HEAD'],
//            'create' => ['POST'],
//            'update' => ['PUT', 'PATCH'],
//            'delete' => ['DELETE'],
//        ];
//    }
//
//
//    public function checkAccess($action, $model = null, $params = [])
//    {
//        $redis = Yii::$app->redis;
//
//        $max_calls_limit  = 3;
//        $time_period      = 10;
//        $total_user_calls = 0;
//
//        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
//            $user_ip_address = $_SERVER['HTTP_CLIENT_IP'];
//        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//            $user_ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
//        } else {
//            $user_ip_address = $_SERVER['REMOTE_ADDR'];
//        }
//
//        if (!$redis->exists($user_ip_address)) {
//            $redis->set($user_ip_address, 1);
//            $redis->expire($user_ip_address, $time_period);
//            $total_user_calls = 1;
//        } else {
//            $redis->INCR($user_ip_address);
//            $total_user_calls = $redis->get($user_ip_address);
//            if ($total_user_calls > $max_calls_limit) {
//                echo "User " . $user_ip_address . " limit exceeded.";
//                exit();
//            }
//        }
//    }
//}
