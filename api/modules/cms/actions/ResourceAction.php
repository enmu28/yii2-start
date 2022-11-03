<?php
namespace api\modules\cms\actions;

use api\modules\cms\constants\ExampleConstant;
use api\modules\cms\helpers\ResourceHelper;
use api\modules\cms\models\elasticsearch\ElasticResource;
use api\modules\cms\models\TtnResource;
use api\modules\cms\helpers\ApiHelper;
use Elasticsearch\ClientBuilder;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\Action;
use yii\web\HttpException;
use Yii;

class ResourceAction extends Action
{
    public $type;

    public function run()
    {
        switch ($this->type) {
            case 'find-all':
                return $this->findAll();
            case 'find-one-by-id':
                return $this->findOneById();
            case 'delete-one-by-id':
                return $this->deleteOneById();
            case 'create-resource':
                return $this->createResource();
            default:
                throw new HttpException('404', 'Page Not Found');
        }
    }

    public function findAll()
    {
        if (q()->isPost) {
            $request = Yii::$app->request->post();
            $news_art_type = isset($request['news_art_type']) ? $request['news_art_type'] : null;
            $type = isset($request['type']) ? $request['type'] : null;
            $status = isset($request['status']) ? $request['status'] : null;
            $name = isset($request['name']) ? $request['name'] : null;
            $sort = isset($request['sort']) ? $request['sort'] : null;
            $created_at_before = isset($request['created_at_before']) ? $request['created_at_before'] : null;
            $created_at_after = isset($request['created_at_after']) ? $request['created_at_after'] : null;

            $data_elas = [];
            if (!empty($news_art_type)) {
                $data_elas[] = [
                    'match' => ['news_art_type' => $news_art_type]
                ];
            }
            if (!empty($type)) {
                $data_elas[] = [
                    'match' => ['type' => $type]
                ];
            }
            if (!empty($status) && $status != 1) {
                $data_elas[] = [
                    'match' => ['status' => $status]
                ];
            } else {
                $data_elas[] = [
                    'match' => ['status' => 1]
                ];
            }
            if (!empty($name)) {
//            tìm theo cụm
                $data_elas[] = [
                    'match' => ['name' => $name]
                ];

//            tìm theo từ khóa
//            $baseQuery['body']['query']['query_string'] = [
//                'default_field' => 'name',
//                'query' => "*lej*"
//            ];
            }

            if (!empty($sort)) {
                $baseQuery['body']['sort'] = [
                    [
                        'created_at' => $sort
                    ]
                ];
            }

            if (!empty($created_at_before) && !empty($created_at_after)) {
                $baseQuery['body']['query']['range'] = [
                    'created_at' => [
                        'gte' => $created_at_before,
                        'lte' => $created_at_after
                    ]
                ];
            }


            $elas = new ElasticResource();
            if(empty($baseQuery)){
                $baseQuery['index'] = 'elastic-resource';
            }

            $data = $elas->search($baseQuery);

            if (!empty($data['data'])) {
                return Json(true, 'Thành công', $data, 200);
            } else {
                return Json(false, 'Thất bại', $data, 400);
            }
        } else {
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }

    }

    public function findOneById()
    {
        if (q()->isGet) {
            $id = Yii::$app->request->get()['id'];
            $elas = new ElasticResource();
            $baseQuery['body']['query']['bool']['must'][] = [
                'match' => ['id' => $id]

            ];
            $data = $elas->search($baseQuery);
            if (!empty($data['data'])) {
                return Json(true, 'Thành công', $data, 200);
            } else {
                return Json(false, 'Thất bại' . (!empty($id)) ? ", thiếu tham số id" : "", $data, 400);
            }
        } else {
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }
    }

    public function deleteOneById()
    {
        if (q()->isPost) {
            $id = Yii::$app->request->post()['id'];
            $newspaper = TtnResource::find()->where(['id' => $id])->one();
            $newspaper->status = 2;
            $newspaper->save();

            $result = ResourceHelper::deleteElasticResource($id);

            if (!empty($result)) {
                return Json(true, 'Đã xóa thành công', null, 200);
            } else {
                return Json(false, 'Thất bại', null, 400);
            }
        } else {
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }
    }

    public function createResource()
    {
//        $resources = [
//            [
//                'resource_type' => 'images/jpg',
//                'resource_name' => 'anh-tuoi-tre',
//                'resource_path' => 'news/anh-tuoi-tre.jpg',
//                'resource_extension' => '.jpg'
//            ],
//            [
//                'resource_type' => 'images/jpg',
//                'resource_name' => 'anh-tuoi-tre-1',
//                'resource_path' => 'news/anh-tuoi-tre.jpg',
//                'resource_extension' => '.jpg'
//            ],
//        ];
//
//        $text = json_encode($resources);
//        var_dump($text); exit();

        $request = Yii::$app->request->post();
//        $array = ['resource_type', 'resource_name', 'resource_path', 'resource_extension'];
//        $validate = ApiHelper::validateInput($array, ExampleConstant::METHOD_POST);

        if (q()->isPost) {
            $data = [];
            $resources = json_decode($request['resources']);
            for($i=0; $i< count($resources); $i++){
                $tr = Yii::$app->db_api->beginTransaction();
                try {
                    $resource = new TtnResource();
                    $resource->type = $resources[$i]->resource_type;
                    $resource->name = $resources[$i]->resource_name;
                    $resource->path = $resources[$i]->resource_path;
                    $resource->extension = $resources[$i]->resource_extension;
                    $resource->created_at = strtotime(date("Ymd"));

                    $result = $resource->save();
                    $tr->commit();
                } catch (\Exception $e) {
                    $tr->rollBack();
                }

                if(!empty($result)){
                    $data[] = $resource->id;
                }
            }

            if (count($data) == count($resources)) {
                ApiHelper::sentQueue($data, ExampleConstant::CREATE_ELASTIC_RESOURCE);
                return Json(true, 'Đã tạo thành công', null, 200);
            } else {
                return Json(false, 'Thất bại', null, 400);
            }
        } else {
            return Json(false, 'Đã xảy ra lỗi', null, 400);
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
//            $newspaper->author = ApiHelper::getUserName();
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
//            return Json(true, 'da chinh thanh cong', null, 200);
//        }else{
//            return Json(false, 'that bai', null, 400);
//        }
//    }

}