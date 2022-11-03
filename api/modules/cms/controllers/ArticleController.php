<?php

namespace api\modules\cms\controllers;

use api\modules\cms\actions\ArticleAction;
use yii\rest\Controller;
use Yii;

class ArticleController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => ArticleAction::class,
                'type' => q()->get('type')
            ],
        ];
    }
}
