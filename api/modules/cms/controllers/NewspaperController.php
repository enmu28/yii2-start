<?php

namespace api\modules\cms\controllers;

use api\modules\cms\actions\NewspaperAction;
use yii\rest\Controller;
use Yii;

class NewspaperController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => NewspaperAction::class,
                'type' => q()->get('type')
            ],

        ];
    }
}
