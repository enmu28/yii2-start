<?php

namespace backend\modules\hello\controllers;

use backend\modules\hello\models\ElasticSlug;
use backend\modules\hello\models\ElasticUser;
use backend\modules\hello\models\SlugAction;
use backend\modules\hello\models\SlugName;
use Elasticsearch\ClientBuilder;
use yii\web\Controller;

class HeyController extends Controller
{
    public function actionSlugUser()
    {
        $users = SlugName::find()->all();

        foreach ($users as $key => $user){
            $elas = new ElasticUser();
            $children = [];
            if(!empty($user->assignment)){
                foreach ($user->assignment as $item => $assignment){
                    $action = SlugAction::find()->where(['id'=> $assignment->slug_action_id])->one();
                    $children[] = [
                        'id' => $action->id,
                        'name' => $action->name,
                        'action_controller' => $action->action_controller,
                        'action_name' => $action->action_name,
                    ];
                }
            }
            $data = [
                'id' => $user->id,
                'user_name' => $user->user_name,
                'name' => $user->name,
                'slug' => $user->slug,
                'children' => $children,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ];
            $elas->insert($data);
        }
    }

    public function actionSlugAction(){
        $actions = SlugAction::find()->all();
        $k = 0;
        $count = count($actions);

        foreach ($actions as $item => $value){
            $elas = new ElasticSlug();
            $data = [
                'id' => $value->id,
                'name' => $value->name,
                'action_controller' => $value->action_controller,
                'action_name' => $value->action_name
            ];
            $result = $elas->insert($data);
            if($result){
                $k +=1;
            }
        }
        if($k == $count){
            var_dump('ahihi do ngoc'); exit();
        }
    }

    public function actionAddChild(){
        $elas = new ElasticUser();
        $children = [
            'id' => 99,
            'name' => '99',
            'action_controller' => 'ahihi',
            'action_name' => 'ahihi'
        ];


    }

    public function actionSearchElastic(){
//        $hosts = [
//            'docker_elasticsearch:9200'
//        ];
//        $client = ClientBuilder::create()
//            ->setHosts($hosts)
//            ->build();
//        $params = [
//            'index' => 'elastic-user',
//            'body'  => [
//                'query' => [
//                    'term' => [
//                        'slug' => \Yii::$app->request->get()['slug'],
//                    ]
//                ]
//            ]
//        ];
//        $response = $client->search($params);
//        var_dump($response); exit();

        $baseQuery['body']['query']['bool']['must'][] = [
            'match' => [
                'slug.keyword' => 'truong-phong-cong-nghe-thong-tin',
            ]
        ];
//        $baseQuery['body']['query']['bool']['must'][] = [
//            'term' => [
//                'action_name' => 'index'
//            ]
//        ];
//        $baseQuery['body']['query']['bool']['must'][] = [
//            'term' => [
//                'action_controller' => 'vendor'
//            ]
//        ];

        $elas = new ElasticUser();
        $data = [];
        $data[] = [
            'match' => [
                'slug.keyword' => 'truong-phong-cong-nghe-thong-tin',
            ]
        ];
        $data[] = [
            'match' => [
                'name' => 'truong phong cong nghe thong tin',
            ]
        ];
        $baseQuery['body']['query']['bool']['must'] = $data;

        $responsive = $elas->search($baseQuery);
        var_dump($responsive); exit();
    }

    public function actionUpdateElastic(){
//        $hosts = [
//            'docker_elasticsearch:9200'
//        ];
//        $client = ClientBuilder::create()
//            ->setHosts($hosts)
//            ->build();
//        $params = [
//            'index' => 'elastic-user',
//            'id'    => '2',
//            'body'  => [
//                'doc' => [
//                    'name' => 'pho phong cong nghe thong tin'
//                ]
//            ]
//        ];
//        $response = $client->update($params);
//        var_dump($response); exit();

        $elas = new ElasticUser();

        $params = [
            'children' => [
                'id' => 1,
                'name' => 'ahihi'
            ]
        ];
        $elas->update($params);
    }

    public function actionDeleteElastic(){
        $hosts = [
            'docker_elasticsearch:9200'
        ];
        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
        $params = [
            'index' => 'elastic-user',
            'id'    => '1'
        ];
        $response = $client->delete($params);
        var_dump($response); exit();
    }

    public function actionDeleteAllElastic(){
        $hosts = [
            'docker_elasticsearch:9200'
        ];
        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
        $params = ['index' => 'elastic-newspaper'];
        $response = $client->indices()->delete($params);
        var_dump($response); exit();
    }
}
