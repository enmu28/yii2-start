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
use app\models\example\TblStyleNo;
use app\models\example\TblVendor;
use yii\web\Controller;
use Yii;

class FormController extends Controller
{
    public function actionIndex()
    {
//        $measurement_system = TblMeasurementSystem::find()->all();
//        $vendor = TblVendor::find()->all();
        $container = new TblContainer();
        $style_no = new TblStyleNo();

        if($container->load(Yii::$app->request->post())){
            $count = 0;
            if($style_no->load(Yii::$app->request->post())){
                $count = count(Yii::$app->request->post('TblStyleNo')['style_no']);
            }

            if($container->validate() && Yii::$app->request->post('TblStyleNo')['style_no'][1]){
                $container->id = Yii::$app->request->post('TblContainer')['id'];
                $container->id_vendor = Yii::$app->request->post('TblContainer')['id_vendor'];
                $container->id_measurement_system = Yii::$app->request->post('TblContainer')['id_measurement_system'];
                $container->price = Yii::$app->request->post('TblContainer')['price'];
                $container->created_at = Yii::$app->request->post('TblContainer')['created_at'];
                $container->save();

                for($i=1; $i<=$count; $i++){
                    $style_no = new TblStyleNo();
                    $style_no->id_container = Yii::$app->request->post('TblContainer')['id'];
                    $style_no->style_no = Yii::$app->request->post('TblStyleNo')['style_no'][$i];
                    $style_no->uom = Yii::$app->request->post('TblStyleNo')['uom'][$i];
                    $style_no->prefix = Yii::$app->request->post('TblStyleNo')['prefix'][$i];
                    $style_no->sufix = Yii::$app->request->post('TblStyleNo')['sufix'][$i];
                    $style_no->height = Yii::$app->request->post('TblStyleNo')['height'][$i];
                    $style_no->width = Yii::$app->request->post('TblStyleNo')['width'][$i];
                    $style_no->length = Yii::$app->request->post('TblStyleNo')['length'][$i];
                    $style_no->weight = Yii::$app->request->post('TblStyleNo')['weight'][$i];
                    $style_no->upc = Yii::$app->request->post('TblStyleNo')['upc'][$i];
                    $style_no->size_1 = Yii::$app->request->post('TblStyleNo')['size_1'][$i];
                    $style_no->color_1 = Yii::$app->request->post('TblStyleNo')['color_1'][$i];
                    $style_no->size_2 = Yii::$app->request->post('TblStyleNo')['size_2'][$i];
                    $style_no->color_2 = Yii::$app->request->post('TblStyleNo')['color_2'][$i];
                    $style_no->size_3 = Yii::$app->request->post('TblStyleNo')['size_3'][$i];
                    $style_no->color_3 = Yii::$app->request->post('TblStyleNo')['color_3'][$i];
                    $style_no->carton = Yii::$app->request->post('TblStyleNo')['carton'][$i];
                    $style_no->save();
                }
            }
            else{
                Yii::$app->session->setFlash('messenge_error', 'Ban chua nhap day du thong tin!');
            }
        }
//        style_no, uom, prefix, sufix, height, width, length, weight, upc, size_1, color_1, size_2, color_2, size_3, color_3,  carton


        return $this->render('index', [
//            'measurement_system' => $measurement_system,
//            'vendor'=> $vendor,
            'style_no' => $style_no,
            'container' => $container
        ]);
    }

    public function actionRedis(){
        $array_a = ['id'=>1, 'a'=>'abc'];
        \Yii::$app->redis->set('arry', $array_a);
//        \Yii::$app->redis->get('arry');
        return 123;
    }
}
