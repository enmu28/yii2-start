<?php

namespace backend\modules\hello\models;
use common\components\elasticsearch\AbstractElasticsearch;


class ElasticUser extends AbstractElasticsearch
{

    public function __construct()
    {
        parent::__construct('docker_elasticsearch:9200');
        $this->_id = 'id';
    }


    public function index(): string
    {
        return 'elastic-user';
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
            'user_name' => ['type' => 'string'],
            'name' => ['type' => 'string'],
            'slug' => ['type' => 'keyword'],
            'children' => [
                'properties' => [
                    'id' => [
                        'type' => 'long',
                    ],
                    'name' => [
                        'type' => 'keyword',
                    ],
                    'action_controller' => [
                        'type' => 'keyword',
                    ],
                    'action_name' => [
                        'type' => 'keyword',
                    ],
                ]
            ],
        ];
    }




}