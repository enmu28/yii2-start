<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace yii\rest;

use api\modules\example\constants\ExampleConstant;
use api\modules\example\models\SlugAction;
use api\modules\example\models\SlugAssignment;
use api\modules\example\models\SlugName;
use backend\modules\hello\helpers\FuckuHelper;
use backend\modules\hello\models\ElasticSlug;
use Elasticsearch\ClientBuilder;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use Yii;
use yii\web\HttpException;

/**
 * ActiveController implements a common set of actions for supporting RESTful access to ActiveRecord.
 *
 * The class of the ActiveRecord should be specified via [[modelClass]], which must implement [[\yii\db\ActiveRecordInterface]].
 * By default, the following actions are supported:
 *
 * - `index`: list of models
 * - `view`: return the details of a model
 * - `create`: create a new model
 * - `update`: update an existing model
 * - `delete`: delete an existing model
 * - `options`: return the allowed HTTP methods
 *
 * You may disable some of these actions by overriding [[actions()]] and unsetting the corresponding actions.
 *
 * To add a new action, either override [[actions()]] by appending a new action class or write a new action method.
 * Make sure you also override [[verbs()]] to properly declare what HTTP methods are allowed by the new action.
 *
 * You should usually override [[checkAccess()]] to check whether the current user has the privilege to perform
 * the specified action against the specified model.
 *
 * For more details and usage information on ActiveController, see the [guide article on rest controllers](guide:rest-controllers).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ActiveController extends Controller
{
    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass;
    /**
     * @var string the scenario used for updating a model.
     * @see \yii\base\Model::scenarios()
     */
    public $updateScenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string the scenario used for creating a model.
     * @see \yii\base\Model::scenarios()
     */
    public $createScenario = Model::SCENARIO_DEFAULT;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->modelClass === null) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => 'yii\rest\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => 'yii\rest\UpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => 'yii\rest\DeleteAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction',
                'checkAccess' => [$this, 'checkAccess'],
            ],
//            'get-id' => [
//                'class' => 'yii\rest\IndexAction',
//                'modelClass' => $this->modelClass,
//                'checkAccess' => [$this, 'checkAccess'],
//            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    /**
     * Checks the privilege of the current user.
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
     *
     * @param string $action the ID of the action to be executed
     * @param object|null $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess($action, $model = null, $params = [])
    {

//        $publicKey = file_get_contents("../web/text/public_key");
//        try{
//            $request = \Yii::$app->request;
//            $token = $request->getHeaders()['authorization'];
//            $jwt = substr($token,7);
//            $decoded = JWT::decode($jwt, new Key($publicKey, 'RS512'));
//            if(!empty($decoded->pos_name)){
//                $slug = FuckuHelper::create_slug("$decoded->pos_name");
//                $slug = str_replace("-", "", $slug);
//
//                $count_action = 0;
//
//                $elas = new ElasticSlug();
//                $baseQuery['body']['query']['bool']['must'][] = [
//                    'term' => [
//                        'slug' => $slug
//                    ]
//                ];
//                $baseQuery['body']['query']['bool']['must'][] = [
//                    'term' => [
//                        'action_name' => Yii::$app->controller->action->id
//                    ]
//                ];
//                $baseQuery['body']['query']['bool']['must'][] = [
//                    'term' => [
//                        'action_controller' => Yii::$app->controller->id
//                    ]
//                ];
//
//                $responsive = $elas->search($baseQuery);
//
//
////                $slug_name = SlugName::find()->where(['like', 'slug', $slug])->one();
////
////                if(!empty($slug_name)){
////                    $slug_assignment = SlugAssignment::find()->where(['slug_name_id'=> $slug_name->id])->all();
////                }
////
////                if(!empty($slug_assignment)){
////                    foreach ($slug_assignment as $key => $value) {
////                        $check = SlugAction::find()->where(['id' => $value->slug_action_id])->one();
////                        if ($check->action_name === Yii::$app->controller->action->id && $check->action_controller == Yii::$app->controller->id) {
////                            $count_action += 1;
////                        }
////                    }
////                }
//
//                if(!empty($responsive['data'])){
//                    return true;
//                }else{
//                    throw new \yii\web\HttpException(401, 'Khong có quyền!.');
//                }
//            }else{
//                throw new \yii\web\ForbiddenHttpException('You can\'t '.$action.' this product 2.');        //403
//            }
//        }catch (Exception $e){
//            throw new \yii\web\HttpException(402, 'cấp lại token key');
//        }
    }
}
