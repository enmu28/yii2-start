<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 8/2/14
 * Time: 11:20 AM
 */

namespace backend\controllers;

use app\models\example\RedisContainer;
use app\models\example\RedisStyleNo;
use app\models\example\TblContainer;
use app\models\example\TblStyleNo;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use Yii;

class FormController extends Controller
{
    public function actionIndex()
    {
        $container = new TblContainer();

        if($container->load(Yii::$app->request->post())){
            $count = count(Yii::$app->request->post('TblStyleNo')['style_no']);

//            && Yii::$app->request->post('TblStyleNo')['style_no'][1]
            if($container->validate()){
                $container->id = Yii::$app->request->post('TblContainer')['id'];
                $container->id_vendor = Yii::$app->request->post('TblContainer')['id_vendor'];
                $container->id_measurement_system = Yii::$app->request->post('TblContainer')['id_measurement_system'];
                $container->price = Yii::$app->request->post('TblContainer')['price'];
                $container->created_at = Yii::$app->request->post('TblContainer')['created_at'];
                $container->save();

//                $vendor = TblVendor::findOne(['id'=> $container->id_vendor]);
//                $measurement_system = TblMeasurementSystem::findOne(['id'=> $container->id_measurement_system]);

                $redis_container = new RedisContainer();
                $redis_container->id = $container->id;
                $redis_container->vendor = $container->id_vendor;
                $redis_container->measurement_system = $container->id_measurement_system;
                $redis_container->price = $container->price;
                $redis_container->created_at = $container->created_at;
                $redis_container->save();

                for($i=1; $i<=$count; $i++){
                    $style_no = new TblStyleNo();
                    $style_no->id_container = Yii::$app->request->post('TblContainer')['id'];
                    $style_no->style_no = Yii::$app->request->post('TblStyleNo')['style_no'][$i];
                    $style_no->uom = Yii::$app->request->post('TblStyleNo')['uom'][$i];
                    $style_no->prefix = Yii::$app->request->post('TblStyleNo')['prefix'][$i];
                    $style_no->sufix = Yii::$app->request->post('TblStyleNo')['sufix'][$i];
                    $style_no->height =  Yii::$app->request->post('TblStyleNo')['height'][$i];
                    $style_no->width = Yii::$app->request->post('TblStyleNo')['width'][$i];
                    $style_no->length = Yii::$app->request->post('TblStyleNo')['length'][$i];
                    $style_no->weight = Yii::$app->request->post('TblStyleNo')['weight'][$i];
                    $style_no->upc = Yii::$app->request->post('TblStyleNo')['upc'][$i];
                    $style_no->size_1 = Yii::$app->request->post('TblStyleNo')['size_1'][$i];
                    $style_no->color_1= Yii::$app->request->post('TblStyleNo')['color_1'][$i];
                    $style_no->size_2 = Yii::$app->request->post('TblStyleNo')['size_2'][$i];
                    $style_no->color_2 = Yii::$app->request->post('TblStyleNo')['color_2'][$i];
                    $style_no->size_3 = Yii::$app->request->post('TblStyleNo')['size_3'][$i];
                    $style_no->color_3 = Yii::$app->request->post('TblStyleNo')['color_3'][$i];
                    $style_no->carton = Yii::$app->request->post('TblStyleNo')['carton'][$i];
                    $style_no->save();

                    $redis_style_no = new RedisStyleNo();
                    $redis_style_no->id = $style_no->id;
                    $redis_style_no->id_container = $style_no->id_container;
                    $redis_style_no->style_no = $style_no->style_no;
                    $redis_style_no->uom = $style_no->uom;
                    $redis_style_no->prefix = $style_no->prefix;
                    $redis_style_no->sufix = $style_no->sufix;
                    $redis_style_no->height = $style_no->height;
                    $redis_style_no->width = $style_no->width;
                    $redis_style_no->length = $style_no->length;
                    $redis_style_no->weight = $style_no->weight;
                    $redis_style_no->upc = $style_no->upc;
                    $redis_style_no->size_1 = $style_no->size_1;
                    $redis_style_no->color_1 = $style_no->color_1;
                    $redis_style_no->size_2 = $style_no->size_2;
                    $redis_style_no->color_2 = $style_no->color_2;
                    $redis_style_no->size_3 = $style_no->size_3;
                    $redis_style_no->color_3 = $style_no->color_3;
                    $redis_style_no->carton = $style_no->carton;
                    $redis_style_no->save();
//                    return redirect()
                }
            }
            else{
                Yii::$app->session->setFlash('messenge_error', 'Ban chua nhap day du thong tin!');
            }
        }
//        style_no, uom, prefix, sufix, height, width, length, weight, upc, size_1, color_1, size_2, color_2, size_3, color_3,  carton


        return $this->render('index', [
//            'style_no' => $style_no,
            'container' => $container
        ]);
    }

    public function actionShow(){
        $query = RedisContainer::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,

            'pagination' => [
                'pageSize' => 1,
            ],
        ]);
        return $this->render('show', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {
        $request = Yii::$app->request;
        $redis_container = RedisContainer::find()->where(['id' => $request->get('id')])->with('styleno')->one();
        return render('view', ['redis_container'=> $redis_container]);
    }

    public function actionUpdate(){
        $request = Yii::$app->request;
        $redis_container = RedisContainer::find()->where(['id' => $request->get('id')])->with('styleno')->one();

        $request_redis = Yii::$app->request->post();
        if($request_redis){
            $container = TblContainer::find()->where(['id'=>$request->get('id')])->one();
            $container->id_vendor = $request_redis['redis_container_vendor'];
            $container->id_measurement_system = $request_redis['redis_container_system'];
            $container->price = $request_redis['redis_container_price'];
            $container->created_at = $request_redis['redis_container_created_at'];
            $container->save();

            $redis_container->vendor =  $request_redis['redis_container_vendor'];
            $redis_container->measurement_system = $request_redis['redis_container_system'];
            $redis_container->price = $request_redis['redis_container_price'];
            $redis_container->created_at = $request_redis['redis_container_created_at'];
            $redis_container->save();

            foreach($redis_container->styleno as $value){
//           style_no, uom, prefix, sufix, height, width, length, weight, upc, size_1, color_1, size_2, color_2, size_3, color_3,  carton
                $style_no = TblStyleNo::find()->where(['id'=> $value->id])->one();
                $style_no->style_no = $request_redis["redis_style_no"][$value->id];
                $style_no->uom = $request_redis["redis_uom"][$value->id];
                $style_no->prefix = $request_redis["redis_prefix"][$value->id];
                $style_no->sufix = $request_redis["redis_sufix"][$value->id];
                $style_no->height = $request_redis["redis_height"][$value->id];
                $style_no->width =$request_redis["redis_width"][$value->id];
                $style_no->length = $request_redis["redis_length"][$value->id];
                $style_no->weight = $request_redis["redis_weight"][$value->id];
                $style_no->upc = $request_redis["redis_upc"][$value->id];
                $style_no->size_1 = $request_redis["redis_size_1"][$value->id];
                $style_no->color_1 = $request_redis["redis_color_1"][$value->id];
                $style_no->size_2 = $request_redis["redis_size_2"][$value->id];
                $style_no->color_2 = $request_redis["redis_color_2"][$value->id];
                $style_no->size_3 = $request_redis["redis_size_3"][$value->id];
                $style_no->color_3 = $request_redis["redis_color_3"][$value->id];
                $style_no->carton = $request_redis["redis_carton"][$value->id];
                $style_no->save();


                $style_no_redis = RedisStyleNo::find()->where(['id' => $value->id])->one();
                $style_no_redis->style_no = $request_redis["redis_style_no"][$value->id];
                $style_no_redis->uom = $request_redis["redis_uom"][$value->id];
                $style_no_redis->prefix = $request_redis["redis_prefix"][$value->id];
                $style_no_redis->sufix = $request_redis["redis_sufix"][$value->id];
                $style_no_redis->height = $request_redis["redis_height"][$value->id];
                $style_no_redis->width =$request_redis["redis_width"][$value->id];
                $style_no_redis->length = $request_redis["redis_length"][$value->id];
                $style_no_redis->weight = $request_redis["redis_weight"][$value->id];
                $style_no_redis->upc = $request_redis["redis_upc"][$value->id];
                $style_no_redis->size_1 = $request_redis["redis_size_1"][$value->id];
                $style_no_redis->color_1 = $request_redis["redis_color_1"][$value->id];
                $style_no_redis->size_2 = $request_redis["redis_size_2"][$value->id];
                $style_no_redis->color_2 = $request_redis["redis_color_2"][$value->id];
                $style_no_redis->size_3 = $request_redis["redis_size_3"][$value->id];
                $style_no_redis->color_3 = $request_redis["redis_color_3"][$value->id];
                $style_no_redis->carton = $request_redis["redis_carton"][$value->id];
                $style_no_redis->save();
            }
            $redis_container = RedisContainer::find()->where(['id' => $request->get('id')])->with('styleno')->one();
            Yii::$app->session->setFlash('success', "Đã cập nhật thông tin thành công!.");
//            return $this->redirect('show');
        }
        return render('update', ['redis_container'=> $redis_container]);
    }


    public function actionDelete(){
        $request = Yii::$app->request;
        $container = TblContainer::findOne($request->get('id'));
        $container->delete();
        $redis_container = RedisContainer::findOne($request->get('id'));
        $redis_container->delete();
        RedisStyleNo::deleteAll(['id_container'=> $request->get('id')]);
    }

    public function actionUpdateRedisContainer(){
        $request = Yii::$app->request->post();
        var_dump($request); exit;
    }
}
