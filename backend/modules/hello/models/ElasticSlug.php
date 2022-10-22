<?php

namespace backend\modules\hello\models;
use common\components\elasticsearch\AbstractElasticsearch;


class ElasticSlug extends AbstractElasticsearch
{

    public function __construct()
    {
        parent::__construct('docker_elasticsearch:9200');
        $this->_id = 'id';
    }


    public function index(): string
    {
        return 'elastic-slug';
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
            'action_controller' => ['type' => 'string'],
            'action_name' => ['type' => 'string'],
        ];
    }




}