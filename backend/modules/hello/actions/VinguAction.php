<?php
/**
 * @author quocdaijr
 * @time 12/06/2020
 * @package backend\modules\content\modules\image\actions
 * @version 1.0
 */

namespace backend\modules\hello\actions;

use backend\modules\hello\models\Post;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use Yii;

class VinguAction extends Action
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
        $tr = Yii::$app->example->beginTransaction();
//        $test = new Post();
//        var_dump($test); exit();
        try {
            for($i=1;$i<=3;$i++){
                $test = new Post();
                $test->title = $i;
                $test->content = $i;
                $test->save();
            }
            if($test->save()){
                $ex = new Post();
                $ex->title = "Ahihi";
                $ex->content = "Ahihi";
                $ex->nem = "Ahihi do ngu";
                $ex->save();
            }
            echo "a hihi do ngoc!";
            $tr->commit();
        } catch (\Exception $e) {
            $tr->rollBack();
            echo  "rollback";
        }

        return $this->controller->render('vingu', $this->render);

    }
}