<?php

namespace api\modules\example\controllers;

use api\modules\example\models\Vendor;
use api\modules\example\models\Container;
use backend\modules\hello\helpers\FuckuHelper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\rest\ActiveController;

class VendorController extends ActiveController
{
    public $modelClass = 'api\modules\example\models\Container';
//    public $modelClass = 'api\modules\example\models\Vendor';
//    public $enableCsrfValidation = false;

//    public function behaviors()
//    {
//        return [
//            'basicAuth' => [
//                'class' => \yii\filters\auth\HttpBasicAuth::class,
//                'auth' => function () {
//                    if(1 == 1){
//                        return true;
//                    }
//                    return 2;
//                },
//            ],
//        ];
//    }

    public function actions() {
        $actions = parent::actions();
//        unset($actions['index']);
        return $actions;
    }

    public function actionIndex() {
        $activeData = new ActiveDataProvider([
//            'query' => Vendor::find(),
            'query' => Container::find(),
            'pagination' => false
        ]);
        return $activeData;
    }

    public function actionGetId(){
        $publicKey = file_get_contents("../web/text/public_key");

//        $payload = [
//            "full_name" => "PHAN NGHỆ ĐÔ",
//            "pos_name" => "Nhan vien phòng Công nghệ Thông tin",
//            "dep_name" => "Phòng Công nghệ Thông tin",
//            "part_name" => "Không",
//            "user_id" => 260,
//            "iat" => 1665123553,
//            "exp" => 1665127153
//        ];
//        $encode = JWT::encode($payload, $publicKey, 'RS512');


        $request = \Yii::$app->request;
        $token = $request->getHeaders()['authorization'];
        $jwt = substr($token,7);
        $decoded = JWT::decode($jwt, new Key($publicKey, 'RS512'));
        $slug = FuckuHelper::create_slug("$decoded->pos_name");



        $id = $request->post('id');
        $activeData = new ActiveDataProvider([
            'query' => Vendor::find()->where(['id'=> $id]),
            'pagination' => false
        ]);


        if($id){
            return [
                'success' => true,
                'message' => \Yii::t('api', 'Show userd.'),
                'data' => $activeData,
                'slug' => $slug
            ];
        }else{
            return [
                'success' => false,
                'message' => \Yii::t('api', 'not user.'),
                'data' => [],
                'slug' => $slug,
                'supplierID' => \Yii::$app->controller->id
            ];
        }
    }

    public function actionAbc(){
        $vendor = Vendor::find()->all();
        return $vendor;
//        var_dump('ahihi');exit();
    }

}
