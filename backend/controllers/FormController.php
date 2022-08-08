<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 8/2/14
 * Time: 11:20 AM
 */

namespace backend\controllers;

use app\models\example\TblContainer;
use app\models\example\TblMeasurementSystem;
use app\models\example\TblSystemNo;
use app\models\example\TblVendor;
use yii\web\Controller;
use Yii;

class FormController extends Controller
{
    public function actionIndex()
    {
        $measurement_system = TblMeasurementSystem::find()->all();
        $vendor = TblVendor::find()->all();
        $container = new TblContainer();
        $style_no = new TblSystemNo;

        if($container->load(Yii::$app->request->post()) && $container->validate()){
            $container->id = Yii::$app->request->post('TblContainer')['id'];
            $container->id_vendor = Yii::$app->request->post('TblContainer')['id_vendor'];
            $container->id_measurement_system = Yii::$app->request->post('TblContainer')['id_measurement_system'];
            $container->price = Yii::$app->request->post('TblContainer')['price'];
            $container->created_at = Yii::$app->request->post('TblContainer')['created_at'];
            $container->save();
            var_dump(Yii::$app->request->post('TblContainer')['price']); exit();
//            Yii::$app->session->setFlash('vinef', 'Nguyen HOan vu');
        }
        return $this->render('index', [
            'measurement_system' => $measurement_system,
            'vendor'=> $vendor,
            'style_no' => $style_no,
            'container' => $container
        ]);
    }
}
