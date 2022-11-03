<?php

namespace api\modules\cms\helpers;

use api\modules\cms\models\elasticsearch\ElasticUser;
use api\modules\cms\models\SlugAction;
use api\modules\cms\models\SlugName;
use Yii;

class UserHelper
{
    public static function updateElasticSlugName($slug_name_id){
        $slug_name = SlugName::find()->where(['id'=> $slug_name_id])->one();
        self::deleteElasticSlugName($slug_name_id);
        $children = [];

        foreach ($slug_name->assignment as $item => $assignment){
            $action = SlugAction::find()->where(['id'=> $assignment->slug_action_id])->one();
            $children[] = [
                'id' => $action->id,
                'name' => $action->name,
                'action_controller' => $action->action_controller,
                'action_name' => $action->action_name,
            ];
        }

        $elas = new ElasticUser();
        $data = [
            'id' => $slug_name->id,
            'name' => $slug_name->name,
            'slug' => $slug_name->slug,
            'children' => $children,
            'created_at' => $slug_name->created_at,
            'updated_at' => strtotime('now'),
        ];

        $result = $elas->insert($data);
        return $result;
    }

    public static function deleteElasticSlugName($id){
        $elas = new ElasticUser();
        $baseQuery['body']['query']['bool']['must'][] = [
            'match' => [
                'id' => $id,
            ]
        ];
        $result = $elas->delete($baseQuery);
        if(!empty($result)){
            return true;
        }else{
            return false;
        }
    }

    public static function createElasticSlugName($id){
        $slug_name = SlugName::find()->where(['id' => $id])->one();
        $elas = new ElasticUser();
        $data = [
            'id' => $slug_name->id,
            'name' => $slug_name->name,
            'slug' => $slug_name->slug,
            'created_at' => $slug_name->created_at
        ];
        $result = $elas->insert($data);
        if(!empty($result)){
            return true;
        }else{
            return false;
        }
    }
}