<?php

namespace api\modules\cms\models\elasticsearch;

use common\components\elasticsearch\AbstractElasticsearch;


class ElasticResource extends AbstractElasticsearch
{

    public function __construct()
    {
        parent::__construct(env('DB_ELASTIC'));
        $this->_id = 'id';
    }


    public function index(): string
    {
        return 'elastic-resource';
    }

    public function settings()
    {
        return [
            'number_of_shards' => 5,
            'number_of_replicas' => 3,
            'analysis' => [
                'analyzer' => [
                    'vietnamese_standard' => [
                        'tokenizer' => 'icu_tokenizer',
                        'filter' => [
                            'icu_folding',
                            'icu_normalizer',
                            'icu_collation'
                        ]
                    ]
                ]
            ]
        ];
    }

    public function map(): array
    {
        return [
            'id' => ['type' => 'long'],
            'name' => ['type' => 'keyword'],
            'type' => ['type' => 'integer'],
            'title' => ['type' => 'string'],
            'extension' => ['type' => 'keyword'],
            'path' => ['type' => 'string'],
            'status' => ['type' => 'integer'],
            'created_at' => ['type' => 'integer'],
            'updated_at' => ['type' => 'integer'],
            'news_art_type' => ['type' => 'string'],
            'news_art_id' => ['type' => 'integer']
        ];
    }




}