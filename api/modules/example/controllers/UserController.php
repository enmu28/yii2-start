<?php

namespace api\modules\example\controllers;

use api\modules\example\models\elasticsearch\ElasticSlug;
use api\modules\example\models\elasticsearch\ElasticUser;
use api\modules\example\models\SlugAction;
use api\modules\example\models\SlugAssignment;
use api\modules\example\models\SlugName;
use api\modules\example\traits\ApiTraits;
use yii\rest\Controller;
use Yii;

class UserController extends Controller
{
    public function actionFindAllSlug() {
        $elas = new ElasticUser();
        $param = [
            'index' => 'elastic-user'
        ];
        $data = $elas->search($param);
        if(!empty($data)){
            return ApiTraits::Json(true, 'thanh cong', $data, 200);
        }else{
            return ApiTraits::Json(false, 'that bai', null, 400);
        }
    }

    public function actionFindAllAction(){
        $elas = new ElasticSlug();
        $param = [
          'index' => 'elastic-slug'
        ];
        $data = $elas->search($param);
        if(!empty($data)){
            return ApiTraits::Json(true, 'thanh cong', $data, 200);
        }else{
            return ApiTraits::Json(false, 'that bai', null, 400);
        }
    }

    public function actionFindOneById($id){
        $elas = new ElasticUser();
        $baseQuery['body']['query']['bool']['must'][] = [
            'match' => [
                'id' => $id,
            ]
        ];
        $data = $elas->search($baseQuery);
        if(!empty($data['data'])) {
            return ApiTraits::Json(true, 'thanh cong', $data, 200);
        }else{
            return ApiTraits::Json(false, 'that bai', $data, 400);
        }
    }

    public function actionDeleteOneById($id){
        SlugName::find()->where(['id'=> $id])->one()->delete();
        $result = $this->deteleElasticSlugName($id);

        if(!empty($result)){
            return ApiTraits::Json(true, 'da xoa thanh cong', null, 200);
        }else{
            return ApiTraits::Json(false, 'that bai', null, 400);
        }
    }

    public function actionAddSlugName(){
        $request = Yii::$app->request->post();
        $user_name = $request['user_name'];
        $name = $request['name'];
        $slug = $request['slug'];

        $slug_name = new SlugName();
        $slug_name->user_name = $user_name;
        $slug_name->name = $name;
        $slug_name->slug = $slug;
        $slug_name->created_at = strtotime('now');
        $result = $slug_name->save();

        if(!empty($result)){
            $elas = new ElasticUser();
            $data = [
              'id' => $slug_name->id,
              'user_name' => $slug_name->user_name,
              'name' => $slug_name->name,
              'slug' => $slug_name->slug,
              'created_at' => $slug_name->created_at
            ];
            $result_1 = $elas->insert($data);
            if(!empty($result_1)){
                return ApiTraits::Json(true, 'da them thanh cong', null, 200);
            }else{
                return ApiTraits::Json(false, 'that bai', null, 400);
            }
        }else{
            return ApiTraits::Json(false, 'that bai', null, 400);
        }

    }

    public function actionAddSlugAction(){
        $request = Yii::$app->request->post();
        $slug_name_id = $request['slug_name_id'];
        $slug_action_id = $request['slug_action_id'];

        $slug_assignment = new SlugAssignment();
        $slug_assignment->slug_name_id = $slug_name_id;
        $slug_assignment->slug_action_id = $slug_action_id;
        $result = $slug_assignment->save();

        if(!empty($result)){
            $result_1 = $this->updateElasticSlugName($slug_name_id);
            if(!empty($result_1)){
                return ApiTraits::Json(true, 'da them thanh cong', null, 200);
            }else{
                return ApiTraits::Json(false, 'that bai', null, 400);
            }
        }else{
            return ApiTraits::Json(false, 'that bai', null, 400);
        }
    }

    public function actionDeleteSlugAction(){
        $request = Yii::$app->request->post();
        $slug_name_id = $request['slug_name_id'];
        $slug_action_id = $request['slug_action_id'];

        $slug_assignment = SlugAssignment::find()->where(['slug_name_id'=> $slug_name_id])->andWhere(['slug_action_id'=> $slug_action_id])->one();
        $result = $slug_assignment->delete();
        if(!empty($result)){
            $result_1 = $this->updateElasticSlugName($slug_name_id);
            if(!empty($result_1)){
                return ApiTraits::Json(true, 'da xoa thanh cong', null, 200);
            }else{
                return ApiTraits::Json(false, 'that bai', null, 400);
            }
        }else{
            return ApiTraits::Json(false, 'that bai', null, 400);
        }
    }
























    public function updateElasticSlugName($slug_name_id){
        $slug_name = SlugName::find()->where(['id'=> $slug_name_id])->one();
        $this->deteleElasticSlugName($slug_name_id);
        $children = [];

        foreach ($slug_name->assignment as $item => $assignment){
            $action = SlugAction::find()->where(['id'=> $assignment->slug_action_id])->one();
            $children[] = [
                'id' => $action->id,
                'name' => $action->name,
                'action_controller' => $action->action_controller,
                'action_name' => $action->action_name,
            ];
        }

        $elas = new ElasticUser();
        $data = [
            'id' => $slug_name->id,
            'user_name' => $slug_name->user_name,
            'name' => $slug_name->name,
            'slug' => $slug_name->slug,
            'children' => $children,
            'created_at' => $slug_name->created_at,
            'updated_at' => strtotime('now'),
        ];

        $result = $elas->insert($data);
        return $result;
    }

    public function deteleElasticSlugName($id){
        $elas = new ElasticUser();
        $baseQuery['body']['query']['bool']['must'][] = [
            'match' => [
                'id' => $id,
            ]
        ];
        $result = $elas->delete($baseQuery);
        if(!empty($result)){
            return true;
        }else{
            return false;
        }
    }


}
