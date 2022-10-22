<?php

namespace api\modules\example\controllers;

use api\modules\example\models\Vendor;
use api\modules\example\models\Container;
use backend\modules\hello\helpers\FuckuHelper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\rest\ActiveController;

class ArticleController extends ActiveController
{
    public function actionFindAll(){

    }
}
