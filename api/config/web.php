<?php
$config = [
    'homeUrl' => Yii::getAlias('@apiUrl'),
    'controllerNamespace' => 'api\controllers',
    'defaultRoute' => 'site/index',
    'bootstrap' => ['maintenance'],
    'modules' => [
        'v1' => \api\modules\v1\Module::class,
        'example' => \api\modules\example\Module::class,
    ],
    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error'
        ],
        'maintenance' => [
            'class' => common\components\maintenance\Maintenance::class,
            'enabled' => function ($app) {
                if (env('APP_MAINTENANCE') === '1') {
                    return true;
                }
                return $app->keyStorage->get('frontend.maintenance') === 'enabled';
            }
        ],
        'user' => [
            'class' => yii\web\User::class,
            'identityClass' => 'api/modules/example/models/vendor',
            'loginUrl' => ['/user/sign-in/login'],
            'enableAutoLogin' => true,
            'as afterLogin' => common\behaviors\LoginTimestampBehavior::class
        ],

        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],

        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // bỏ index
            'enablePrettyUrl' => true,
            // bỏ ?r =
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'example\vendor',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST get-id' => 'example\vendor\get-id',
//                        'GET '
                    ],
                ],
            ],

        ],
        'jwt' => [
            'class' => 'bizley\jwt\Jwt',
            'key' => 'secret' // Secret key string or path to the public key file
        ],
    ]
];

return $config;
