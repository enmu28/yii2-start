<?php

namespace backend\modules\hello\actions;

use yii\base\Action;
use yii\web\HttpException;
use Yii;

class DongocAction extends Action
{
    public $type;

    public function run()
    {
        switch ($this->type) {
            case 'get-ahihi':
                return $this->getAhihi();
            case 'get-ahi':
                return $this->getAhi();
            default:
                throw new HttpException('404', 'Page Not Found');
        }
    }

    public function getAhihi(){
        return 'Ahihi Stupid!';
    }

    public function getAhi(){
        return 'Ahi Stupid!';
    }
}