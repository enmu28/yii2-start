<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 8/2/14
 * Time: 11:20 AM
 */

namespace backend\controllers;

use backend\jobs\JobExample;
use yii\web\Controller;
use Yii;

class AhihiController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->queue->push(new JobExample([
            'ahihi' => 'Ahihi do ngoc ne! Queue job :))',
        ]));
//        return "Ahihi ddo ngoc!";
    }
}
