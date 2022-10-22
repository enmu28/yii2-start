<?php

namespace backend\modules\hello\controllers;

use app\models\elastic\Post;
use backend\modules\hello\actions\AhihiAction;
use backend\modules\hello\actions\OcchoAction;
use backend\modules\hello\actions\VinguAction;
use yii\web\Controller;

class HiController extends Controller
{
    public function actions(): array
    {
        return [
            'dongoc' => [
                'class' => AhihiAction::class,
            ],
            'vingu' => [
                'class' => VinguAction::class
            ],
            'occho' => [
                'class' => OcchoAction::class,
            ]
        ];
    }
}
