<?php

namespace api\modules\cms\helpers;

use api\modules\cms\models\elasticsearch\ElasticResource;
use api\modules\cms\models\TtnResource;
use Elasticsearch\ClientBuilder;
use Yii;

class ResourceHelper
{
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
    }
}