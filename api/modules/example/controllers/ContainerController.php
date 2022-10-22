<?php
/**
 * @author quocdaijr
 * @time 14/02/2020
 * @package api\modules\user\controllers
 * @version 1.0
 */

namespace api\modules\example\controllers;

use api\modules\example\actions\ContainerAction;

use yii\rest\Controller;


class ContainerController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $behaviors['authenticator']['authMethods'] = [
//            HttpBearerAuth::class
//        ];

        $behaviors['authenticator'] = [
            'class' => \bizley\jwt\JwtHttpBearerAuth::class,
        ];

        return $behaviors;
    }

    public function actions(): array
    {
        return [
            'dongoc' => [
                'class' => ContainerAction::class,
            ],
        ];
    }
}