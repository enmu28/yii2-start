<?php
/**
 * @author quocdaijr
 * @time 09/03/2020
 * @package backend\modules\content\helpers
 * @version 1.0
 */

namespace api\modules\example\traits;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\web\HttpException;

class ApiTraits
{
    public static function Json($status, $message, $data, $http)
    {
        return [
          'status' => $status,
          'message' => $message,
          'data' => $data['data'],
          'https' => $http
        ];
    }

    public static function getUserName(){
        $publicKey = file_get_contents("../web/text/public_key");
        $request = \Yii::$app->request;
        $token = $request->getHeaders()['authorization'];
        $jwt = substr($token,7);
        $decoded = JWT::decode($jwt, new Key($publicKey, 'RS512'));
        return $decoded->full_name;
    }
}