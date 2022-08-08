<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 8/2/14
 * Time: 11:20 AM
 */

namespace backend\controllers;

use app\models\example\TblVendor;
use yii\web\Controller;
use Yii;

class ExampleController extends Controller
{
    public function actionIndex()
    {
//        $data = TblVendor::find()->all();
        $data = Yii::$app->example->createCommand("SELECT * FROM `ex_tbl_measurement_system`")->queryAll();
        var_dump($data); exit;
//        var_dump($data); exit;
//        Yii::$app->db_storage
//        return $this->render('index');
    }
}
