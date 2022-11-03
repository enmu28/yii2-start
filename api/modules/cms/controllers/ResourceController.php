<?php

namespace api\modules\cms\controllers;

use api\modules\cms\actions\ResourceAction;
use yii\rest\Controller;
use Yii;

class ResourceController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => ResourceAction::class,
                'type' => q()->get('type')
            ],

        ];
    }

}
