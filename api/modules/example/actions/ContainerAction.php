<?php
/**
 * @author quocdaijr
 * @time 12/06/2020
 * @package backend\modules\content\modules\image\actions
 * @version 1.0
 */

namespace api\modules\example\actions;

use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use Yii;

class ContainerAction extends Action
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
        return "ahihi";
    }
}