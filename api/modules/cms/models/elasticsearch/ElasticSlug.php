<?php

namespace api\modules\cms\models\elasticsearch;

use common\components\elasticsearch\AbstractElasticsearch;


class ElasticSlug extends AbstractElasticsearch
{

    public function __construct()
    {
        parent::__construct(env('DB_ELASTIC'));
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
            'action_index' => ['type' => 'string'],
        ];
    }




}