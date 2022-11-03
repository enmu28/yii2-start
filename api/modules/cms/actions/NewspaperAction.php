<?php

namespace api\modules\cms\actions;

use api\modules\cms\constants\ExampleConstant;
use api\modules\cms\helpers\NewspaperHelper;
use api\modules\cms\models\elasticsearch\ElasticNewspaper;
use api\modules\cms\models\TtnMap;
use api\modules\cms\models\TtnNewspaper;
use api\modules\cms\models\TtnResource;
use api\modules\cms\helpers\ApiHelper;
use yii\base\Action;
use Yii;
use yii\web\HttpException;

class NewspaperAction extends Action
{
    public $type;

    public function run()
    {
        switch ($this->type) {
            case 'find-newspaper':
                return $this->findNewspaper();
            case 'find-one-by-id':
                return $this->findOneById();
            case 'delete-one-by-id':
                return $this->deleteOneById();
            case 'create-newspaper':
                return $this->createNewspaper();
            case 'edit-newspaper':
                return $this->editNewspaper();
            default:
                throw new HttpException('404', 'Page Not Found');
        }
    }

    public function findNewspaper() {
        if(q()->isPost){
            $request = Yii::$app->request->post();
            $category_id = isset($request['category_id']) ? $request['category_id'] : null;
            $date = isset($request['date']) ? $request['date'] : null;


            $elas = new ElasticNewspaper();
            if(!empty($category_id)){
                $baseQuery['body']['query']['bool']['must'][] = [
                    'match' => [
                        'category' => $category_id,
                    ]
                ];
            }
            if(!empty($date)){
                $baseQuery['body']['query']['bool']['must'][] = [
                    'match' => [
                        'published_at' => $date,
                        // date format strtotime(date('Ymd'))
                    ]
                ];
            }

            $baseQuery['index'] = 'elastic-newspaper';
            $data = $elas->search($baseQuery);

            if(!empty($data['data'])){
                return Json(true, 'Thành công', $data, 200);
            }else{
                return Json(false, 'Thất bại', null, 400);
            }
        }else{
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }
    }
//    public function actionFindByDate($date){
//        if(q()->isGet){
//            $elas = new ElasticNewspaper();
//            $baseQuery['body']['query']['bool']['must'][] = [
//                'match' => [
//                    'published_at' => $date,   //strtotime date('Ymd')
//                ]
//            ];
//            $data = $elas->search($baseQuery);
//            if(!empty($data['data'])) {
//                return Json(true, 'thanh cong', $data, 200);
//            }else{
//                return Json(false, 'that bai', $data, 400);
//            }
//        }else{
//            return Json(false, 'da xay ra loi', null, 400);
//        }
//
//    }
//
    public function findOneById(){
        $id = Yii::$app->request->get()['id'];
        if(q()->isGet){
            $elas = new ElasticNewspaper();
            $baseQuery['body']['query']['bool']['must'][] = [
                'match' => [
                    'id' => $id,
                ]
            ];
            $data = $elas->search($baseQuery);
            if(!empty($data['data'])) {
                return Json(true, 'Thành công', $data, 200);
            }else{
                return Json(false, 'Thất bại', null, 400);
            }
        }else{
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }
    }

    public function deleteOneById(){
        $action_name = Yii::$app->controller->action->id;
//        var_dump($this->type); exit();
        if(q()->isPost){
            $id = Yii::$app->request->post()['id'];
            $newspaper = TtnNewspaper::find()->where(['id'=> $id])->one();
            $newspaper->status = 2;
            $result = $newspaper->save();
            $list_resouce_count = 0;

//            $this->deleteElasticNewspaper($id);
            NewspaperHelper::deleteElasticNewspaper($id);

            $resource = $newspaper->resource;
            $db = TtnResource::find()->where(['id'=> $resource->id])->one();
            $db->status = 2;
            $result_resource = $db->save();
//            $this->deleteElasticResource($resource->id);
            NewspaperHelper::deleteElasticResource($resource->id);

//        foreach ($list_resouce as $item => $resource){
//            $db = TtnResource::find()->where(['id'=> $resource->id])->one();
//            $db->status = 2;
//            $result_resource = $db->save();
//            if($result_resource){
//                $list_resouce_count = $list_resouce_count + 1;
//            }
//
//            $this->deleteElasticResource($resource->id);
//        }


            if(!empty($result) && !empty($result_resource)){
                ApiHelper::setLogAction($newspaper->id, ExampleConstant::TABLE_NEWSPAPER, $this->type);
                return Json(true, 'Đã xóa thành công', null, 200);
            }else{
                return Json(false, 'Thất bại', null, 400);
            }
        }else{
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }

    }

    public function createNewspaper(){
        $request = Yii::$app->request->post();
        $array = ['resource_type', 'resource_name', 'resource_path', 'resource_extension', 'type', 'published_at', 'color', 'category_id', 'page_number'];
        $validate = ApiHelper::validateInput($array, ExampleConstant::METHOD_POST);

        if(q()->isPost && $validate == null) {
            $resource_type = $request['resource_type'];
            $resource_name = $request['resource_name'];
            $resource_path = $request['resource_path'];
            $resource_extension = $request['resource_extension'];

            $type = $request['type'];
            $published_at = $request['published_at'];
            $color = $request['color'];
            $category_id = $request['category_id'];
            $page_number = $request['page_number'];


            $tr = Yii::$app->db_api->beginTransaction();
            try {
                $newspaper = new TtnNewspaper();
                $newspaper->type = $type;
                $newspaper->published_at = $published_at;
                $newspaper->color = $color;
                $newspaper->category_id = $category_id;
                $newspaper->page_number = $page_number;
                $newspaper->author = ApiHelper::getUserName();
                $result = $newspaper->save();

                if (!empty($result)) {
                    $resource = new TtnResource();
                    $resource->news_article_id = $newspaper->id;
                    $resource->news_article_type = ExampleConstant::RESOURCE_TYPE_NEWS;
                    $resource->type = $resource_type;
                    $resource->name = $resource_name;
                    $resource->path = $resource_path;
                    $resource->extension = $resource_extension;
                    $resource->created_at = strtotime(date("Ymd"));


                    $result_1 = $resource->save();
                    // ???????????????? chmod
//                $upload_file = $file->saveAs( Yii::getAlias('@uploads'). '/' .$file->name);
                }
                $tr->commit();
            } catch (\Exception $e) {
                $tr->rollBack();
            }

//                $result_elas = $this->createElasticNewspaper($newspaper->id);
//                $result_elas_1 = $this->createElasticResource($resource->id);

//                $result_elas = NewspaperHelper::createElasticNewspaper($newspaper->id);
//                $result_elas_1 = NewspaperHelper::createElasticResource($resource->id);


            if (!empty($result_1)) {
                $data = [];
                $data['id_newspaper'] = $newspaper->id;
                $data['id_resource'] = $resource->id;
                ApiHelper::sentQueue($data, ExampleConstant::CREATE_ELASTIC_NEWSPAPER);

                ApiHelper::setLogAction($newspaper->id, ExampleConstant::TABLE_NEWSPAPER, $this->type);
                return Json(true, 'Đã thêm thành công', null, 200);
            } else {
                return Json(false, 'Thất bại', null, 400);
            }
        }else{
            return Json(false, $validate, null, 400);
        }
    }

    public function editNewspaper(){
//        $map = [
//            [
//                'html' => 'abc',
//                'html_svg' => 'abc'
//            ],
//            [
//                'html' => 'def',
//                'html_svg' => 'def'
//            ]
//        ];
//
//        $json_encode = json_encode($map);
//        $json = json_decode($json_encode);
//        var_dump($json_encode);
//        exit();

        if(q()->isPost){
            $request = Yii::$app->request->post();

            $id_news = $request['id_news'];
            $type = $request['type'];
            $published_at = $request['published_at'];
            $color = $request['color'];
            $category_id = $request['category_id'];
            $page_number = $request['page_number'];
            $map = $request['map'];

//            $this->deleteElasticNewspaper($id_news);
            NewspaperHelper::deleteElasticNewspaper($id_news);
            $count_map = 0;

            $tr = Yii::$app->db_api->beginTransaction();
            try {

                $tr->commit();
            } catch (\Exception $e) {
                $tr->rollBack();
            }
            $newspaper = TtnNewspaper::find()->where(['id'=> $id_news])->one();
            $newspaper->type = $type;
            $newspaper->published_at = $published_at;
            $newspaper->color = $color;
            $newspaper->category_id = $category_id;
            $newspaper->page_number = $page_number;
            $newspaper->author = ApiHelper::getUserName();
            $result = $newspaper->save();

            if(!empty($result)){
                $resource_id = $newspaper->resource->id;
                TtnMap::deleteAll(['resource_id'=> $resource_id]);
                if(!empty($map)){
                    $map_decode = json_decode($map);
                    for($i = 0; $i<count($map_decode); $i++){
                        $db_map = new TtnMap();
                        $db_map->resource_id = $resource_id;
                        $db_map->html_map = $map_decode[$i]->html;
                        $db_map->html_svg = $map_decode[$i]->html_svg;
                        $result = $db_map->save();
                        if(!empty($result)){
                            $count_map = $count_map + 1;
                        }
                    }
                }
            }

            // check dieu kien !empty cac gia tri param
            if($count_map == count(json_decode($map))){
//                $this->createElasticNewspaper($id_news);
                NewspaperHelper::createElasticNewspaper($id_news);
                ApiHelper::setLogAction($newspaper->id, ExampleConstant::TABLE_NEWSPAPER, $this->type);
                return Json(true, 'Đã chỉnh thành công', null, 200);
            }else{
                return Json(false, 'Thất bại', null, 400);
            }
        }else{
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }

    }
}