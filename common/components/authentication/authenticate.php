<?php

namespace common\components\authentication;

use backend\modules\hello\helpers\FuckuHelper;
use backend\modules\hello\models\ElasticSlug;
use yii\db\Exception;
use yii\filters\auth\AuthMethod;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;
use yii\web\HttpException;
use yii\web\ForbiddenHttpException;

class authenticate extends AuthMethod
{

    public $realm = 'api';

    public $auth;


    public function authenticate($user, $request, $response)
    {
        $publicKey = file_get_contents("../web/text/public_key");
        $request = \Yii::$app->request;
        $token = $request->getHeaders()['authorization'];
        if(empty($token)){
            throw new HttpException(401, 'Not token key');
        }
        $jwt = substr($token,7);
        try{
            $decoded = JWT::decode($jwt, new Key($publicKey, 'RS512'));
        }catch (\Exception $e){
            if($e->getMessage() == 'Incorrect key for this algorithm'){
                throw new HttpException(401, 'Incorrect key for this algorithm');
            }
//            elseif ($e->getMessage() == 'Signature verification failed'){
//                throw new HttpException(401, 'Signature verification failed');
//            }
            elseif ($e->getMessage() == 'Expired token'){
                throw new HttpException(401, 'Token expired');
            }else{
                throw new HttpException(401, 'Signature verification failed');
            }
        }
        if(!empty($decoded->pos_name)){
            $slug = FuckuHelper::create_slug("$decoded->pos_name");

            $elas = new ElasticSlug();
            $baseQuery['body']['query']['bool']['must'][] = [
                'match' => [
                    'slug.keyword' => $slug,
                ]
            ];
            $baseQuery['body']['query']['bool']['must'][] = [
                'match' => [
                    'action_controller' => Yii::$app->controller->id,
                ]
            ];
            $baseQuery['body']['query']['bool']['must'][] = [
                'match' => [
                    'action_name' => Yii::$app->controller->action->id,
                ]
            ];

            $responsive = $elas->search($baseQuery);

            if(!empty($responsive['data'])){
                return true;
            }else{
                throw new HttpException(401, 'Permission denied!.');
            }
        } else{
            throw new ForbiddenHttpException('Not found!.');        //403
        }
    }

}