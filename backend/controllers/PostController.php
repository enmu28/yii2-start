<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 8/2/14
 * Time: 11:20 AM
 */

namespace backend\controllers;

use app\models\example\Post;
use backend\jobs\JobExample;
use yii\web\Controller;
use Yii;

class PostController extends Controller
{
   public function actionAddPost(){
//        if(Yii::$app->queue->delay(10)->push(new JobExample())){
//            echo "okey";
//        }else{
//            echo "not okey";
//        }
       Yii::$app->queue->push(new JobExample());

       return "okey";
   }
}
