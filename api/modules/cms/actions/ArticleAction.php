<?php
namespace api\modules\cms\actions;

use api\modules\cms\constants\ExampleConstant;
use api\modules\cms\helpers\ApiHelper;
use api\modules\cms\helpers\ArticleHelper;
use api\modules\cms\models\elasticsearch\ElasticArticle;
use api\modules\cms\models\elasticsearch\ElasticResource;
use api\modules\cms\models\TtnArticle;
use api\modules\cms\models\TtnResource;
use Elasticsearch\ClientBuilder;
use yii\base\Action;
use yii\web\HttpException;
use Yii;

class ArticleAction extends Action
{
    public $type;

    public function run()
    {
        switch ($this->type) {
            case 'find-article':
                return $this->findArticle();
            case 'find-one-by-id':
                return $this->findOneById();
            case 'delete-one-by-id':
                return $this->deleteOneById();
            case 'create-article':
                return $this->createArticle();
            case 'edit-article':
                return $this->editArticle();
            default:
                throw new HttpException('404', 'Page Not Found');
        }
    }

    public function findArticle() {
        if(q()->isPost){
            $request = Yii::$app->request->post();
            $published_at = isset($request['published_at']) ? $request['published_at'] : null;
            $category_id = isset($request['category_id']) ? $request['category_id'] : null;

            $data_elas = [];
            if(!empty($date)){
                $data_elas[] = [
                    'match' => ['published_at' => $published_at]
                ];
            }
            if(!empty($category_id)){
                $data_elas[] = [
                    'match' => ['category' => $category_id]
                ];
            }


            $elas = new ElasticArticle();
            if(!empty($data_elas)){
                $baseQuery['body']['query']['bool']['must'][] = $data_elas;
            }else{
                $baseQuery['index'] = 'elastic-article';
            }
            $data = $elas->search($baseQuery);

            if(!empty($data['data'])){
                return Json(true, 'Thành công', $data, 200);
            }else{
                return Json(false, 'Không có dữ liệu', null, 400);
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
            $elas = new ElasticArticle();
            $baseQuery['body']['query']['bool']['must'][] = [
                'match' => [
                    'id' => $id,
                ]
            ];
            $data = $elas->search($baseQuery);
            if(!empty($data['data'])) {
                return Json(true, 'Thành công', $data, 200);
            }else{
                return Json(false, 'Thất bại', $data, 400);
            }
        }else{
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }
    }

    public function deleteOneById(){
        if(q()->isPost){
            $request = Yii::$app->request->post();
            $id = $request['id'];
            $article = TtnArticle::find()->where(['id'=> $id])->one();
            $article->status = 2;
            $result = $article->save();
            $list_resouce_count = 0;

            ArticleHelper::deleteElasticArt($id);

            $list_resource = $article->resource;
//            $db = TtnResource::find()->where(['id'=> $resource->id])->one();
//            $db->status = 2;
//            $result_resource = $db->save();
//            $this->deleteElasticResource($resource->id);

            foreach ($list_resource as $item => $resource){
                $db = TtnResource::find()->where(['id'=> $resource->id])->one();
                $db->status = 2;
                $result_resource = $db->save();
                if($result_resource){
                    $list_resouce_count = $list_resouce_count + 1;
                }

                ArticleHelper::deleteElasticResource($resource->id);
//                $this->deleteElasticResource($resource->id);
            }


            if(!empty($result) && $list_resouce_count == count($list_resource)){
                return Json(true, 'Đã xoá thành công', null, 200);
            }else{
                return Json(false, 'Thất bại', null, 400);
            }
        }else{
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }

    }

    public function createArticle(){
        $request = Yii::$app->request->post();
        $array = ['thumbnail_type', 'thumbnail_name', 'thumbnail_path', 'thumbnail_extension', 'content', 'type', 'published_at', 'category_id', 'page_number'];
        $validate = ApiHelper::validateInput($array, ExampleConstant::METHOD_POST);
        $data = [];

        if(q()->isPost && $validate == null){
//            $thumbnail = UploadedFile::getInstanceByName('thumbnail');
            $thumbnail_type = $request['thumbnail_type'];
            $thumbnail_name = $request['thumbnail_name'];
            $thumbnail_path = $request['thumbnail_path'];
            $thumbnail_extension = $request['thumbnail_extension'];

            $content = $request['content'];
            $type = $request['type'];
            $published_at = $request['published_at'];
            $category_id = $request['category_id'];
            $page_number = $request['page_number'];

            $tr = Yii::$app->db_api->beginTransaction();
            try {
                $article = new TtnArticle();
                $article->content = $content;
                $article->type = $type;
                $article->published_at = $published_at;
                $article->category_id = $category_id;
                $article->page_number = $page_number;
                $article->author = ApiHelper::getUserName();
                $result = $article->save();

                $data['id_art'] = $article->id;

                if(!empty($result)){
                    $resource = new TtnResource();
                    $resource->news_article_id = $article->id;
                    $resource->news_article_type = ExampleConstant::RESOURCE_TYPE_ART;
                    $resource->thumbnail = ExampleConstant::USE_THUMBNAIL;
                    $resource->type = $thumbnail_type;
                    $resource->name = $thumbnail_name;
                    $resource->path = $thumbnail_path;
                    $resource->extension = $thumbnail_extension;
                    $resource->created_at = strtotime(date('Ymd'));

                    $result_1 = $resource->save();
                    $data['id_resource'] = $resource->id;

                    // ????????????????
//                    $upload_file = $thumbnail->saveAs( Yii::getAlias('@uploads'). '/' .$thumbnail->name);
                }

                $tr->commit();
            } catch (\Exception $e) {
                $tr->rollBack();
            }


//            if(isset($result_1)){
//                $result_elas = ArticleHelper::createElasticArt($article->id);
//                $result_elas_1 = ArticleHelper::createElasticResource($resource->id);

//                $result_elas  = $this->createElasticArt($article->id);
//                $result_elas_1 = $this->createElasticResource($resource->id);
//            }

            if(!empty($result_1)){
                ApiHelper::sentQueue($data, ExampleConstant::CREATE_ELASTIC_ARTICLE);
                return Json(true, 'Đã thêm thành công', null, 200);
            }else{
                return Json(false, 'Thất bại', null, 400);
            }
        }else{
            return Json(false, $validate, null, 400);
        }
    }

    public function editArticle(){
        $data = [];
        if(q()->isPost){
//        var_dump(dirname(__DIR__, 2) . '/uploads'); exit();
//        var_dump(Yii::getAlias('@uploads')); exit();
            $request = Yii::$app->request->post();
            $id_art = $request['id'];
            $content = $request['content'];
            $type = $request['type'];
            $published_at = $request['published_at'];
            $category_id = $request['category_id'];
            $page_number = $request['page_number'];

//            $this->deleteElasticArt($id_art);
            ArticleHelper::deleteElasticArt($id_art);
            $tr = Yii::$app->db_api->beginTransaction();
            try {
                $article = TtnArticle::find()->where(['id'=> $id_art])->one();
                $article->content = $content;
                $article->type = $type;
                $article->published_at = $published_at;
                $article->category_id = $category_id;
                $article->page_number = $page_number;
                $article->author = ApiHelper::getUserName();
                $result = $article->save();

                $data['id'] = $article->id;

                $tr->commit();
            } catch (\Exception $e) {
                $tr->rollBack();
            }

            if(!empty($result)){
                ApiHelper::sentQueue($data, ExampleConstant::CREATE_ELASTIC_ARTICLE);
                return Json(true, 'Đã chỉnh thành công', null, 200);
            }else{
                return Json(false, 'Thất bại', null, 400);
            }
        }else{
            return Json(false, 'Đã xảy ra lỗi', null, 400);
        }

    }
}