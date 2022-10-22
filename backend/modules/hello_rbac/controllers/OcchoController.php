<?php

namespace backend\modules\hello_rbac\controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\web\Controller;
use backend\modules\hello\traits\FuckuTrait;
use backend\modules\hello\helpers\FuckuHelper;
use Yii;

class OcchoController extends Controller
{
    public function behaviors()
    {
//        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => CompositeAuth::class,
//            'user' => z()->auth,
//            'authMethods' => [
//                HttpBasicAuth::class,
//                HttpBearerAuth::class,
//                HttpHeaderAuth::class,
//                QueryParamAuth::class
//            ]
//        ];
//        return $behaviors;

//        var_dump($behaviors); exit();
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['index', 'ahihi'],
//                'rules' => [
//                    [
//                        'actions' => ['ahihi','index'],
//                        'allow' => true,
//                        'roles' => ['manager-occho'],
//                        'matchCallback' => function ($rule, $action)
//                        {
//                            echo "string";
//                            die;
//                        },
//                    ],
//                ],
//                'denyCallback' => function ($rule, $action) {
//                    return $this->redirect(Yii::$app->request->baseUrl);
//                }
//            ],
//        ];
    }

    public function actionAhihi()
    {
//        $publicKey = <<<EOD
//-----BEGIN PUBLIC KEY-----
//MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArtFHqu1mmHa8q0o53oUT
//5z7XHZPWG6qUc//qtwabCizRObewJeNZI5o8BGZzJ6NaEWfppe8woAD7u1mA9Z7c
//X1N385ntKHbgVebJIWa6p4LBFTWXPJ13OtqyQH1E+uCoKe5tzNgBaaE5eGulpo2p
//azfozhLnhriKFwbDEdloSyA5fWjb1hrS0TQPBpbAgVZrXTEFP2z3o/P8Dzx3rR2a
//Sv+AP4wV50ERZwfjQtbkjuIv5JIj+rY1zqCJkONLCJK+vp+Rd0cfOL/Pidm6In8k
//PMmgPQSc0TXbJtm+EwcWIXDT1IdWNFkQIG/+Z/LYaOcueKBkzZXP4fbc3qTuVwdU
//qwIDAQAB
//-----END PUBLIC KEY-----
//EOD;
//
//        $jwt = 'eyJhbGciOiJSUzUxMiIsInR5cCI6IkpXVCJ9.eyJmdWxsX25hbWUiOiJQSEFOIE5HSOG7hiDEkMOUIiwicG9zX25hbWUiOiJUcsaw4bufbmcgcGjDsm5nIEPDtG5nIG5naOG7hyBUaMO0bmcgdGluIiwiZGVwX25hbWUiOiJQaMOybmcgQ8O0bmcgbmdo4buHIFRow7RuZyB0aW4iLCJwYXJ0X25hbWUiOiJLaMO0bmciLCJ1c2VyX2lkIjoyNjAsImlhdCI6MTY2NTAyODUzMSwiZXhwIjoxNjY1MDMyMTMxfQ.jdiqVdSOruuxA2TPLmH213ZOW-WzzUFxoezbFMMi2O8dianMkUN1JlppdI4_bradRtL1_5GGHiiZuetk3IbSiRf9mKQkQcwXnQsc-1FnV0-t_ny2DeLZjEuRtt7r-emqndeegpDLl55erKAeczrMfFN6vg7TpDRFEnSx8y5OpEZ-I1z1VJW4I3DEvADZhXuegB2YYtPrMLr0DwZL64owGBQBHSku1Dc0iav_PZkBYrutzsxL2E73GWkYR4oXsJxl1CdWrG52xCS92Nl7i6c2uDEMAJv8U15oqrw70ab4_7aoo6jfRCYDtJZcKN_jFWgVgvj71urKFXsCkmpugvhIxQ';
//        $decoded = JWT::decode($jwt, new Key($publicKey, 'RS512'));
//        $slug = FuckuHelper::create_slug("$decoded->pos_name");
//        var_dump($slug); exit();
        return "Ahihi do ngoc!";
    }

    public function actionIndex()
    {
        return "Index Ahihi do ngoc!";
    }

    public function actionUpdate()
    {
        return "Update Ahihi do ngoc!";
    }

    public function actionDelete()
    {
        return "Delete Ahihi do ngoc!";
    }
}
