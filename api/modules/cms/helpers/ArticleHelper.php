<?php

namespace api\modules\cms\helpers;

use api\modules\cms\constants\ExampleConstant;
use api\modules\cms\models\elasticsearch\ElasticArticle;
use api\modules\cms\models\elasticsearch\ElasticResource;
use api\modules\cms\models\TtnArticle;
use api\modules\cms\models\TtnResource;
use Elasticsearch\ClientBuilder;
use Yii;

class ArticleHelper
{
    public static function createElasticArt($id_art){
//        $this->deleteElasticNewspaper($id_news);
        $elas = new ElasticArticle();
        $data_resource = null;

        $art = TtnArticle::find()->where(['id'=> $id_art])->one();

        $resource = $art->resource;
        if(!empty($resource)){
            foreach($resource as $item => $value){
                $data_resource[] = [
                    'id' => $value->id,
                    'type' => $value->type,
                    'thumbnail' => $value->thumbnail ? ExampleConstant::THUMBNAIL : "",
                    'name' => $value->name,
                    'path' => $value->path,
                    'extension' => $value->extension,
                    'status' => $value->status,
                    'created_at' => $value->created_at,
                    'updated_at' => $value->updated_at,
                ];
            }
        }
        $data = [
            'id' => $art->id,
            'published_at' => $art->published_at,
            'type' => $art->type,
            'content' => $art->content,
            'category' => $art->category_id,
            'page_number' => $art->page_number,
            'author' => $art->author,
            'status' => $art->status,
            'resource' => $data_resource,
        ];

        $result = $elas->insert($data);
        if(!empty($result)){
            return true;
        }else{
            return false;
        }
    }

    public static function createElasticResource($id_resource){
        $resource = TtnResource::find()->where(['id' => $id_resource])->one();
        $elas = new ElasticResource();
        $data = [
            'id' => $resource->id,
            'type' => $resource->type,
            'thumbnail' => $resource->thumbnail ? ExampleConstant::THUMBNAIL : "",
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

    public static function deleteElasticArt($id_art){
        $hosts = [
            env('DB_ELASTIC')
        ];
        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
        $params = [
            'index' => 'elastic-article',
            'id'    => $id_art,
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

    public static function deleteElasticResource($id_resource){
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