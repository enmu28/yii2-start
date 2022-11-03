<?php
//
//namespace api\modules\cms\controllers;
//
//use api\modules\cms\actions\ContainerAction;
//
//use yii\rest\Controller;
//
//
//class ContainerController extends Controller
//{
//    /**
//     * @return array
//     */
//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
////        $behaviors['authenticator']['authMethods'] = [
////            HttpBearerAuth::class
////        ];
//
//        $behaviors['authenticator'] = [
//            'class' => \bizley\jwt\JwtHttpBearerAuth::class,
//        ];
//
//        return $behaviors;
//    }
//
//    public function actions(): array
//    {
//        return [
//            'dongoc' => [
//                'class' => ContainerAction::class,
//            ],
//        ];
//    }
//}