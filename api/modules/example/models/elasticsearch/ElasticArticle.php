<?php
//
//namespace api\modules\example\models\elasticsearch;
//
//use common\components\elasticsearch\AbstractElasticsearch;
//
//
//class ElasticArticle extends AbstractElasticsearch
//{
//
//    public function __construct()
//    {
//        parent::__construct(env('DB_ELASTIC'));
//        $this->_id = 'id';
//    }
//
//
//    public function index(): string
//    {
//        return 'elastic-article';
//    }
//
//    public function settings()
//    {
//        return [
//            'number_of_shards' => 5,
//            'number_of_replicas' => 3,
//            'analysis' => [
//                'analyzer' => [
//                    'vietnamese_standard' => [
//                        'tokenizer' => 'icu_tokenizer',
//                        'filter' => [
//                            'icu_folding',
//                            'icu_normalizer',
//                            'icu_collation'
//                        ]
//                    ]
//                ]
//            ]
//        ];
//    }
//
//    public function map(): array
//    {
//        return [
//            'id' => ['type' => 'long'],
//            'category' => ['type' => 'keyword'],
//            'type' => ['type' => 'string'],
//            'color' => ['type' => 'string'],
//            'author' => ['type' => 'string'],
//            'page_number' => ['type' => 'integer'],
//            'status' => ['type' => 'integer'],
//            'published_at' => ['type' => 'integer'],
//            'map' => [
//                'properties' => [
//                    'id' => [
//                        'type' => 'long',
//                    ],
//                    'html_map' => [
//                        'type' => 'text',l
//                    ],
//                    'html_svg' => [
//                        'type' => 'text',
//                    ],
//                ]
//            ],
//            'resource' => [
//                'properties' => [
//                    'id' => [
//                        'type' => 'long'
//                    ],
//                    'name' => [
//                        'type' => 'keyword'
//                    ],
//                    'type' => [
//                        'type' => 'integer'
//                    ],
//                    'title' => [
//                        'type' => 'string'
//                    ],
//                    'extension' => [
//                        'type' => 'keyword'
//                    ],
//                    'path' => [
//                        'type' => 'string'
//                    ],
//                    'created_at' => [
//                        'type' => 'integer'
//                    ],
//                    'updated_at' => [
//                        'type' => 'integer'
//                    ],
//                ]
//            ]
//        ];
//    }
//}