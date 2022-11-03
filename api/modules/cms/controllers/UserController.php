<?php

namespace api\modules\cms\controllers;

use api\modules\cms\actions\UserAction;
use yii\rest\Controller;
use Yii;

class UserController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => UserAction::class,
                'type' => q()->get('type')
            ],
        ];
    }
}
