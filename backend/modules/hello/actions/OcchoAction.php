<?php
/**
 * @author quocdaijr
 * @time 12/06/2020
 * @package backend\modules\content\modules\image\actions
 * @version 1.0
 */

namespace backend\modules\hello\actions;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\base\Action;
use Yii;

class OcchoAction extends Action
{
    /**
     * @var array
     */
    public $render = [];

    public function run()
    {
        return $this->renderContent();
    }

    protected function renderContent()
    {
//        chmod("../modules/hello/actions/AhihiAction.php", 0755);
//        $abc = file_get_contents("../web/text/public_key");
//        $abc = explode($abc);
//        $abc = getdate()[0];

        return "abc";
    }
}