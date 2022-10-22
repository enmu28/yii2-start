<?php
/**
 * @author quocdaijr
 * @time 12/06/2020
 * @package backend\modules\content\modules\image\actions
 * @version 1.0
 */

namespace backend\modules\hello\actions;

//use app\models\db_api\SlugAssignment;
//use app\models\db_api\SlugName;
use app\models\elastic\Post;

use backend\modules\hello\models\ElasticSlug;
use backend\modules\hello\models\ElasticUser;
use backend\modules\hello\models\SlugAction;
use backend\modules\hello\models\SlugAssignment;
use backend\modules\hello\models\SlugName;
use Elasticsearch\ClientBuilder;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use Yii;

class AhihiAction extends Action
{
    /**
     * @var array
     */
    public $render = [];

    /**
     * @throws ForbiddenHttpException
     */
    public function run()
    {
        return $this->renderContent();
    }

    /**
     * @throws ForbiddenHttpException
     */
    protected function renderContent()
    {
        var_dump('ahihi do ngoc!'); exit();
    }
}