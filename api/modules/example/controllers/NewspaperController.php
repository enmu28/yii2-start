<?php

namespace api\modules\example\controllers;

use api\modules\example\constants\ExampleConstant;
use api\modules\example\models\elasticsearch\ElasticNewspaper;
use api\modules\example\models\elasticsearch\ElasticResource;
use api\modules\example\models\elasticsearch\ElasticSlug;
use api\modules\example\models\elasticsearch\ElasticUser;
use api\modules\example\models\SlugName;
use api\modules\example\models\TtnMap;
use api\modules\example\models\TtnNewspaper;
use api\modules\example\models\TtnResource;
use api\modules\example\traits\ApiTraits;
use Elasticsearch\ClientBuilder;
use yii\rest\Controller;
use Yii;
use yii\web\UploadedFile;

class NewspaperController extends Controller
{
    public function actionFindAll() {
        $elas = new ElasticNewspaper();
        $param = [
            'index' => 'elastic-newspaper'
        ];
        $data = $elas->search($param);
        if(!empty($data)){
            return ApiTraits::Json(true, 'thanh cong', $data, 200);
        }else{
            return ApiTraits::Json(false, 'that bai', null, 400);
        }
    }

    public function actionFindByDate($date){
        $elas = new ElasticNewspaper();
        $baseQuery['body']['query']['bool']['must'][] = [
            'match' => [
                'published_at' => $date,   //strtotime date('Ymd')
            ]
        ];
        $data = $elas->search($baseQuery);
        if(!empty($data['data'])) {
            return ApiTraits::Json(true, 'thanh cong', $data, 200);
        }else{
            return ApiTraits::Json(false, 'that bai', $data, 400);
        }
    }

    public function actionFindOneById($id){
        $elas = new ElasticNewspaper();
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
        $newspaper = TtnNewspaper::find()->where(['id'=> $id])->one();
        $newspaper->status = 2;
        $result = $newspaper->save();
        $list_resouce_count = 0;

        $this->deteleElasticNewspaper($id);


        $resource = $newspaper->resource;
        $db = TtnResource::find()->where(['id'=> $resource->id])->one();
        $db->status = 2;
        $result_resource = $db->save();
        $this->deleteElasticResource($resource->id);

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

            return ApiTraits::Json(true, 'da xoa thanh cong', null, 200);
        }else{
            return ApiTraits::Json(false, 'that bai', null, 400);
        }
    }

    public function actionCreateNewspaper(){
        $request = Yii::$app->request->post();
        $file = UploadedFile::getInstanceByName('file');
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
            $newspaper->author = ApiTraits::getUserName();
            $result = $newspaper->save();

            if(!empty($result)){
                $resource = new TtnResource();
                $resource->news_article_id = $newspaper->id;
                $resource->news_article_type = ExampleConstant::RESOURCE_TYPE_NEWS;
                $resource->type = $file->type;
                $resource->name = $file->getBaseName();
                $resource->path = 'uploads/'.$file->name;
                $resource->extension = $file->getExtension();
                $resource->created_at = strtotime("3 October 2005");

//                    strtotime(date("Ymd"));

                $result_1 = $resource->save();

                // ???????????????? chmod
//                @chmod(Yii::getAlias('@uploads'), 0777);
                $upload_file = $file->saveAs( Yii::getAlias('@uploads'). '/' .$file->name);
            }
            $tr->commit();
        } catch (\Exception $e) {
            $tr->rollBack();
        }
        $newspaper = new TtnNewspaper();
        $newspaper->type = $type;
        $newspaper->published_at = $published_at;
        $newspaper->color = $color;
        $newspaper->category_id = $category_id;
        $newspaper->page_number = $page_number;
        $newspaper->author = ApiTraits::getUserName();
        $result = $newspaper->save();

        if(!empty($result)){
            $resource = new TtnResource();
            $resource->news_article_id = $newspaper->id;
            $resource->news_article_type = ExampleConstant::RESOURCE_TYPE_NEWS;
            $resource->type = $file->type;
            $resource->name = $file->getBaseName();
            $resource->path = 'uploads/'.$file->name;
            $resource->extension = $file->getExtension();
            $resource->created_at = '1128297601';

//                    strtotime(date("Ymd"));

            $result_1 = $resource->save();

            // ???????????????? chmod
//                @chmod(Yii::getAlias('@uploads'), 0777);
            $upload_file = $file->saveAs( Yii::getAlias('@uploads'). '/' .$file->name);
        }

        if(isset($result_1) && isset($upload_file)){
            $result_elas  = $this->createElasticNewspaper($newspaper->id);

            $result_elas_1 = $this->createElasticResource($resource->id);

            if(!empty($result_elas) && !empty($result_elas_1)){
                return ApiTraits::Json(true, 'da them thanh cong', null, 200);
            }else{
                return ApiTraits::Json(false, 'that bai', null, 400);
            }
        }else{
            return ApiTraits::Json(false, 'that bai', null, 400);
        }

    }

    public function actionEditNewspaper($id_news){
//        var_dump(dirname(__DIR__, 2) . '/uploads'); exit();
//        var_dump(Yii::getAlias('@uploads')); exit();
        $this->deteleElasticNewspaper($id_news);
        $request = Yii::$app->request->post();

        $type = $request['type'];
        $published_at = $request['published_at'];
        $color = $request['color'];
        $category_id = $request['category_id'];
        $page_number = $request['page_number'];

        $map = $request['map'];
        $count_map = 0;

        $tr = Yii::$app->db_api->beginTransaction();
        try {
            $newspaper = TtnNewspaper::find()->where(['id'=> $id_news])->one();
            $newspaper->type = $type;
            $newspaper->published_at = $published_at;
            $newspaper->color = $color;
            $newspaper->category_id = $category_id;
            $newspaper->page_number = $page_number;
            $newspaper->author = ApiTraits::getUserName();
            $result = $newspaper->save();

            if(!empty($result)){
                $resource_id = $newspaper->resource->id;
                TtnMap::deleteAll(['resource_id'=> $resource_id]);
                if(!empty($map)){
                    for($i= 0; $i<count($map); $i++){
                        $db_map = new TtnMap();
                        $db_map->resource_id = $resource_id;
                        $db_map->html_map = $map[$i]['html_map'];
                        $db_map->html_svg = $map[$i]['html_svg'];
                        $result = $db_map->save();
                        if(!empty($result)){
                            $count_map = $count_map + 1;
                        }
                    }
                }
            }
            $tr->commit();
        } catch (\Exception $e) {
            $tr->rollBack();
        }
        if($count_map == count($map)){
            $this->createElasticNewspaper($id_news);
            return ApiTraits::Json(true, 'da chinh thanh cong', null, 200);
        }else{
            return ApiTraits::Json(false, 'that bai', null, 400);
        }
    }








    //---------------------------------------------------------------
    public function createElasticNewspaper($id_news){
//        $this->deteleElasticNewspaper($id_news);
        $elas = new ElasticNewspaper();
        $data_resource = null;
        $data_map = [];

        $news = TtnNewspaper::find()->where(['id'=> $id_news])->one();
        $resource = $news->resource;
        if(!empty($resource)){
            $data_resource = [
              'id' => $resource->id,
              'type' => $resource->type,
              'name' => $resource->name,
              'path' => $resource->path,
              'extension' => $resource->extension,
              'status' => $resource->status,
              'created_at' => $resource->created_at,
              'updated_at' => $resource->updated_at,
            ];

            $map = TtnResource::find()->where(['id'=> $resource->id])->one()->map;
            if(!empty($map)){
                foreach ($map as $item => $value){
                    $data_map[] = [
                        'id' => $value->id,
                        'resource_id' => $value->resource_id,
                        'html_map' => $value->html_map,
                        'html_svg' => $value->html_svg
                    ];
                }
            }
        }
        $data = [
            'id' => $news->id,
            'published_at' => $news->published_at,
            'color' => $news->color,
            'category' => $news->category->name,
            'page_number' => $news->page_number,
            'author' => $news->author,
            'status' => $news->status,
            'resource' => $data_resource,
            'map' => $data_map
        ];

        $result = $elas->insert($data);
        if(!empty($result)){
            return true;
        }else{
            return false;
        }
    }

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

    public function deteleElasticNewspaper($id_news){
        $hosts = [
            env('DB_ELASTIC')
        ];
        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
        $params = [
            'index' => 'elastic-newspaper',
            'id'    => $id_news,
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
//        $elas = new ElasticNewspaper();
//        $baseQuery['body']['query']['bool']['must'][] = [
//            'match' => [
//                'id' => $id_news,
//            ]
//        ];
//        $result = $elas->delete($baseQuery);
//        if(!empty($result)){
//            return true;
//        }else{
//            return false;
//        }
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
//        $elas = new ElasticResource();
//        $baseQuery['body']['query']['bool']['must'][] = [
//            'match' => [
//                'id' => $id_resource
//            ]
//        ];
//        $result = $elas->delete($baseQuery);
//        if(!empty($result)){
//            return true;
//        }else{
//            return false;
//        }

    }


}
