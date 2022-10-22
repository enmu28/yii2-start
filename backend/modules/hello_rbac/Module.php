<?php

namespace backend\modules\hello_rbac;

/**
 * article module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\hello_rbac\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
