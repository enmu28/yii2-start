<?php

namespace backend\modules\hello;

/**
 * article module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\hello\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
