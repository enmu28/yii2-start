<?php

namespace backend\modules\hello\controllers;

use backend\modules\hello\actions\DongocAction;
use yii\web\Controller;

class DongocController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => DongocAction::class,
                'type' => q()->get('type')
            ],

        ];
    }
}
