<?php
namespace api\modules\cms\actions;

use api\modules\cms\constants\ExampleConstant;
use api\modules\cms\helpers\UserHelper;
use api\modules\cms\models\elasticsearch\ElasticSlug;
use api\modules\cms\models\elasticsearch\ElasticUser;
use api\modules\cms\models\SlugAction;
use api\modules\cms\models\SlugAssignment;
use api\modules\cms\models\SlugName;
use api\modules\cms\helpers\ApiHelper;
use yii\base\Action;
use yii\web\HttpException;
use Yii;

class UserAction extends Action
{
    public $type;

    public function run()
    {
        switch ($this->type) {
            case 'find-all-slug':
                return $this->findAllSlug();
            case 'fin-one-by-id':
                return $this->findOneById();
            case 'delete-one-by-id':
                return $this->deleteOneById();
            case 'add-slug_name':
                return $this->addSlugName();

            case 'find-all-action':
                return $this->findAllAction();
            case 'add-slug-action':
                return $this->addSlugAction();
            case 'delete-slug-action':
                return $this->deleteSlugAction();
            default:
                throw new HttpException('404', 'Page Not Found');
        }
    }

    public function findAllSlug() {
        if (q()->isGet){
            $elas = new ElasticUser();
            $param = [
                'index' => 'elastic-user'
            ];
            $data = $elas->search($param);
            if(!empty($data)){
                return Json(true, 'Thành công', $data, 200);
            }else{
                return Json(false, 'Thất bại', null, 400);
            }
        }
        else{
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }
    }

    public function findAllAction(){
        if(q()->isGet){
            $elas = new ElasticSlug();
            $param = [
                'index' => 'elastic-slug'
            ];
            $data = $elas->search($param);
            if(!empty($data)){
                return Json(true, 'Thành công!', $data, 200);
            }else{
                return Json(false, 'Thất bại!', null, 400);
            }
        }else{
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }

    }

    public function findOneById(){
        if(q()->isGet){
            $id = Yii::$app->request->get()['id'];

            $elas = new ElasticUser();
            $baseQuery['body']['query']['bool']['must'][] = [
                'match' => [
                    'id' => $id,
                ]
            ];
            $data = $elas->search($baseQuery);
            if(empty($id)) {
                return Json(true, 'Thiếu tham số $id', null, 400);
            }elseif(!empty($data['data'])){
                return Json(true, 'Thành công', $data, 200);
            }else{
                return Json(false, 'Thất bại', null, 400);
            }
        }else{
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }
    }

    public function deleteOneById(){
        if(q()->isPost){
            $id = Yii::$app->request->post()['id'];
            if(!empty($id)){
                $slug_name = SlugName::find()->where(['id'=> $id])->one();
                if(!empty($slug_name)){
                    $result = $slug_name->delete();
                }

//                $this->deleteElasticSlugName($id);
                UserHelper::deleteElasticSlugName($id);

                if(!empty($result)){
                    return Json(true, 'Đã xóa thành công', null, 200);
                }else{
                    return Json(false, 'Thất bại', null, 400);
                }
            }
            else{
                return Json(false, 'Chưa có tham số $id', null, 400);
            }
        }else{
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }
    }

    public function addSlugName(){
        $validate = ApiHelper::validateInput(['name', 'slug'], ExampleConstant::METHOD_POST);

        if(q()->isPost && $validate == null){
            $request = Yii::$app->request->post();
            $name = $request['name'];
            $slug = $request['slug'];

            $slug_name = new SlugName();
            $slug_name->name = $name;
            $slug_name->slug = $slug;
            $slug_name->created_at = strtotime(date('Ymd'));
            $result = $slug_name->save();

            if(!empty($result)){
                $elas = new ElasticUser();
                $data = [
                    'id' => $slug_name->id,
                    'name' => $slug_name->name,
                    'slug' => $slug_name->slug,
                    'created_at' => $slug_name->created_at
                ];
                $result_1 = $elas->insert($data);
            }
            if(!empty($result_1)){
                return Json(true, 'Đã thêm thành công', null, 200);
            }else{
                return Json(false, 'Thất bại', null, 400);
            }
        }else{
            return Json(false, $validate, null, 400);
        }
    }

    public function addSlugAction(){
        $validate = ApiHelper::validateInput(['slug_name_id', 'slug_action_id'], ExampleConstant::METHOD_POST);

        if(q()->isPost && $validate == null){
            $request = Yii::$app->request->post();
            $slug_name_id = $request['slug_name_id'];
            $slug_action_id = $request['slug_action_id'];

            $slug_assignment = new SlugAssignment();
            $slug_assignment->slug_name_id = $slug_name_id;
            $slug_assignment->slug_action_id = $slug_action_id;
            $result = $slug_assignment->save();

            if(!empty($result)){
                UserHelper::updateElasticSlugName($slug_name_id);
//                $this->updateElasticSlugName($slug_name_id);
                return Json(true, 'Đã thêm thành công', null, 200);
            }else{
                return Json(false, 'Thất bại', null, 400);
            }
        }else{
            return Json(false, $validate, null, 400);
        }

    }

    public function deleteSlugAction(){
        if(q()->isPost){
            $request = Yii::$app->request->post();
            $slug_name_id = $request['slug_name_id'];
            $slug_action_id = $request['slug_action_id'];

            $slug_assignment = SlugAssignment::find()->where(['slug_name_id'=> $slug_name_id])->andWhere(['slug_action_id'=> $slug_action_id])->one();
            $result = $slug_assignment->delete();
            if(!empty($result)){
//                $result_1 = $this->updateElasticSlugName($slug_name_id);
                $result_1 = UserHelper::updateElasticSlugName($slug_name_id);
            }

            if(!empty($result_1)){
                return Json(true, 'Đã xóa thành công', null, 200);
            }else{
                return Json(false, 'Thất bại', null, 400);
            }
        }else{
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }
    }




















//    public function actionCreateSlugName(){
//        if(q()->isPost){
//            $request = Yii::$app->request->post();
//            $name = $request['name'];
//            $slug = $request['slug'];
//
//            $slug_name = new SlugName();
//            $slug_name->name = $name;
//            $slug_name->slug = $slug;
//            $slug_name->created_at = strtotime(date("Ymd"));
//            $result = $slug_name->save();
//
//            if(!empty($result)){
//                $elas = new ElasticUser();
//                $data = [
//                    'id' => $slug_name->id,
//                    'name' => $slug_name->name,
//                    'slug' => $slug_name->slug,
//                    'children' => null,
//                    'created_at' => $slug_name->created_at,
//                ];
//
//                $result_1 = $elas->insert($data);
//            }
//            if(!empty($result_1)){
//                return Json(true, 'tao thanh cong!', null, 200);
//            }else{
//                return Json(false, 'that badi', null, 400);
//            }
//        }else{
//            return Json(false, 'sai phuong thuc', null, 400);
//        }
//    }

//    public function actionCreateSlugAction(){
//        if(q()->isPost){
//            $request = Yii::$app->request->post();
//            $name = $request['name'];
//            $action_controller = $request['action_controller'];
//            $action_name = $request['action_name'];
//
//            $slug_action = new SlugAction();
//            $slug_action->name = $name;
//            $slug_action->action_controller = $action_controller;
//            $slug_action->action_name = $action_name;
//            $result = $slug_action->save();
//
//            if(!empty($result)){
//                $elas = new ElasticSlug();
//                $data = [
//                    'id' => $slug_action->id,
//                    'name' => $slug_action->name,
//                    'action_controller' => $slug_action->action_controller,
//                    'action_name' => $slug_action->action_name,
//                ];
//                $result_1 = $elas->insert($data);
//            }
//            if(!empty($result_1)){
//                return Json(true, 'tao thanh cong!', null, 200);
//            }else{
//                return Json(false, 'that badi', null, 400);
//            }
//        }else{
//            return Json(false, 'sai phuong thuc', null, 400);
//        }
//    }
}