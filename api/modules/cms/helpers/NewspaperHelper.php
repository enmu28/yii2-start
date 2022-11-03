<?php

namespace api\modules\cms\helpers;

use api\modules\cms\models\elasticsearch\ElasticNewspaper;
use api\modules\cms\models\elasticsearch\ElasticResource;
use api\modules\cms\models\TtnNewspaper;
use api\modules\cms\models\TtnResource;
use Elasticsearch\ClientBuilder;
use Yii;

class NewspaperHelper
{
    public static function createElasticNewspaper($id_news){
//        $this->deleteElasticNewspaper($id_news);
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
            'type' => $news->type,
            'color' => $news->color,
            'category' => $news->category_id,
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

    public static function createElasticResource($id_resource){
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

    public static function deleteElasticNewspaper($id_news){
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