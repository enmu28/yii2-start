<?php

namespace api\modules\example\controllers;

use api\modules\example\models\elasticsearch\ElasticResource;
use api\modules\example\models\TtnResource;
use api\modules\example\traits\ApiTraits;
use Elasticsearch\ClientBuilder;
use yii\rest\Controller;
use Yii;
use yii\web\UploadedFile;

class ResourceController extends Controller
{
//    public function actionFindAll() {
//        $elas = new ElasticResource();
//        $param = [
//            'index' => 'elastic-resource',
//        ];
//        $data = $elas->search($param);
//        if(!empty($data)){
//            return ApiTraits::Json(true, 'thanh cong', $data, 200);
//        }else{
//            return ApiTraits::Json(false, 'that bai', null, 400);
//        }
//    }

    public function actionFindAll(){
        $request = Yii::$app->request->get();
        $news_art_type = isset($request['news_art_type']) ? $request['news_art_type'] : null;
        $type = isset($request['type']) ? $request['type'] : null;
        $status = isset($request['status']) ? $request['status'] : null;
        $name = isset($request['name']) ? $request['name'] : null;
        $sort = isset($request['sort']) ? $request['sort'] : null;
        $created_at = isset($request['created_at']) ? $request['created_at'] : null;

        $data_elas = [];
        if(!empty($news_art_type)){
            $data_elas[] = [
                'match' => [
                    'news_art_type' => $news_art_type,
                ]
            ];
        }
        if(!empty($type)){
            $data_elas[] = [
                'match' => ['type.keyword' => $type]
            ];
        }
        if(!empty($status) && $status != 1){
            $data_elas[] = [
                'match' => ['status' => $status]
            ];
        }else{
            $data_elas[] = [
                'match' => ['status' => 1]
            ];
        }
        if(!empty($name)){
            // tìm theo cụm
            $data_elas[] = [
                'match' => ['name' => $name]
            ];

//            tìm theo từ khóa
//            $baseQuery['body']['query']['query_string'] = [
//                'default_field' => 'name',
//                'query' => "*lej*"
//            ];
        }
        if(!empty($created_at)){
            $data_elas[] = [
                'match' => [
                    'created_at' => $created_at
                ]
            ];
        }
        if(!empty($sort)){
            $baseQuery['body']['sort'] = [
                [
                    'created_at' => $sort
                ]
            ];
        }

        $elas = new ElasticResource();
        if(!empty($data_elas)){
            $baseQuery['body']['query']['bool']['must'][] = $data_elas;
        }else{
            $baseQuery['index'] = 'elastic-resource';
        }
        $data = $elas->search($baseQuery);

        if(!empty($data['data'])) {
            return ApiTraits::Json(true, 'thanh cong', $data, 200);
        }else{
            return ApiTraits::Json(false, 'that bai', $data, 400);
        }
    }

    public function actionFindOneById($id){
        $elas = new ElasticResource();
        $baseQuery['body']['query']['bool']['must'][] = [
            'match' => ['id' => $id]

        ];
        $data = $elas->search($baseQuery);
        if(!empty($data['data'])) {
            return ApiTraits::Json(true, 'thanh cong', $data, 200);
        }else{
            return ApiTraits::Json(false, 'that bai', $data, 400);
        }
    }

    public function actionDeleteOneById($id){
        $newspaper = TtnResource::find()->where(['id'=> $id])->one();
        $newspaper->status = 2;
        $newspaper->save();

        $result = $this->deleteElasticResource($id);

        if(!empty($result)){
            return ApiTraits::Json(true, 'da xoa thanh cong', null, 200);
        }else{
            return ApiTraits::Json(false, 'that bai', null, 400);
        }
    }

    public function actionCreateResource(){
        $file = UploadedFile::getInstanceByName('file');

        $tr = Yii::$app->db_api->beginTransaction();
        try {
            $resource = new TtnResource();
            $resource->type = $file->type;
            $resource->name = $file->getBaseName();
            $resource->path = 'uploads/'.$file->name;
            $resource->extension = $file->getExtension();
            $resource->created_at = strtotime(date("Ymd"));

            $result = $resource->save();

            // ???????????????? chmod
//                @chmod(Yii::getAlias('@uploads'), 0777);
            $upload_file = $file->saveAs( Yii::getAlias('@uploads'). '/' .$file->name);
            $tr->commit();
        } catch (\Exception $e) {
            $tr->rollBack();
        }

        if(!empty($result) && !empty($upload_file)){
            $result_elas = $this->createElasticResource($resource->id);

            if(!empty($result_elas)){
                return ApiTraits::Json(true, 'da tao thanh cong', null, 200);
            }else{
                return ApiTraits::Json(false, 'that bai', null, 400);
            }
        }else{
            return ApiTraits::Json(false, 'that bai', null, 400);
        }

    }

//    public function actionEditNewspaper($id_news){
////        var_dump(dirname(__DIR__, 2) . '/uploads'); exit();
////        var_dump(Yii::getAlias('@uploads')); exit();
//        $request = Yii::$app->request->post();
//
//        $type = $request['type'];
//        $published_at = $request['published_at'];
//        $color = $request['color'];
//        $category_id = $request['category_id'];
//        $page_number = $request['page_number'];
//
//        $map = $request['map'];
//        $count_map = 0;
//
//        $tr = Yii::$app->db_api->beginTransaction();
//        try {
//            $newspaper = TtnNewspaper::find()->where(['id'=> $id_news])->one();
//            $newspaper->type = $type;
//            $newspaper->published_at = $published_at;
//            $newspaper->color = $color;
//            $newspaper->category_id = $category_id;
//            $newspaper->page_number = $page_number;
//            $newspaper->author = ApiTraits::getUserName();
//            $result = $newspaper->save();
//
//            if(!empty($result)){
//                $resource_id = $newspaper->resource->id;
//                TtnMap::deleteAll(['resource_id'=> $resource_id]);
//                if(!empty($map)){
//                    for($i= 0; $i<count($map); $i++){
//                        $db_map = new TtnMap();
//                        $db_map->resource_id = $resource_id;
//                        $db_map->html_map = $map[$i]['html_map'];
//                        $db_map->html_svg = $map[$i]['html_svg'];
//                        $result = $db_map->save();
//                        if(!empty($result)){
//                            $count_map = $count_map + 1;
//                        }
//                    }
//                }
//            }
//            $tr->commit();
//        } catch (\Exception $e) {
//            $tr->rollBack();
//        }
//        if($count_map == count($map)){
//            $this->createElasticNewspaper($id_news);
//            return ApiTraits::Json(true, 'da chinh thanh cong', null, 200);
//        }else{
//            return ApiTraits::Json(false, 'that bai', null, 400);
//        }
//    }








    //---------------------------------------------------------------

    public function createElasticResource($id_resource){
        $resource = TtnResource::find()->where(['id' => $id_resource])->one();
        $elas = new ElasticResource();
        $data = [
            'id' => $resource->id,
            'type' => $resource->type,
            'name' => $resource->name,
            'title' => $resource->title,
            'path' => $resource->path,
            'extension' => $resource->extension,
            'status' => $resource->status,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
            'news_art_id' => $resource->news_article_id,
            'news_art_type' => $resource->news_article_type
        ];

        $result = $elas->insert($data);
        if(!empty($result)){
            return true;
        }else{
            return false;
        }
    }

    public function deleteElasticResource($id_resource){
        $hosts = [
            env('DB_ELASTIC')
        ];
        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
        $params = [
            'index' => 'elastic-resource',
            'id'    => $id_resource,
            'body'  => [
                'doc' => [
                    'status' => 2
                ]
            ]
        ];
        $result = $client->update($params);
        if(!empty($result)){
            return true;
        }else{
            return false;
        }
    }

}
