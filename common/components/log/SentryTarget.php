<?php
/**
 * Created by PhpStorm.
 * User: viktor <apacheservices68@gmail.com>
 * @package common\components\log
 */

namespace common\components\log;

use yii\base\InvalidConfigException;
use yii\base\InvalidRouteException;
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use yii\log\Target;
use yii\web\NotFoundHttpException;
use Sentry;
use Yii;
use yii\web\Request;
use Sentry\Severity;

class SentryTarget extends Target
{
    /**
     * @var string Sentry client key.
     */
    public $dsn;
    /**
     * @var array Options of the \Raven_Client.
     */
    public $clientOptions = [];
    /**
     * @var bool Write the context information. The default implementation will dump user information, system variables, etc.
     */
    public $context = false;
    /**
     * @var callable Callback function that can modify extra's array
     */
    public $extraCallback;

    /**
     * @var array
     */
    protected $ignores = [
        NotFoundHttpException::class,
        InvalidRouteException::class,
//        HttpException::class
    ];

    private function register() {
        if (empty($this->dsn)) {
            throw new InvalidConfigException(' DSN must be configured in order to send information to Sentry');
        }
        Sentry\init(array_merge(['dsn' => $this->dsn], $this->clientOptions));
    }

    public function collect($messages, $final) {
        $this->register();
        parent::collect($messages, $final);
    }

    /**
     * @inheritdoc
     */
    protected function getContextMessage()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        foreach ($this->messages as $message) {
            list($text, $level, $category, $timestamp, $traces) = $message;
            if (is_object($text) && !empty($type_exception = get_class($text)) && in_array($type_exception, $this->ignores))
                return false;

            $data = [
                'level' => static::getLevelName($level),
                'timestamp' => $timestamp,
                'tags' => ['category' => $category],
                'extra' => [],
                'userData' => [],
            ];

            $request = Yii::$app->getRequest();
            if ($request instanceof Request && $request->getUserIP()) {
                $data['userData']['ip_address'] = $request->getUserIP();
            }

            Sentry\withScope(function (Sentry\State\Scope $scope) use ($text, $level, $data) {
                if (is_array($text)) {
                    if (isset($text['msg'])) {
                        $data['message'] = $text['msg'];
                        unset($text['msg']);
                    }

                    if (isset($text['tags'])) {
                        $data['tags'] = ArrayHelper::merge($data['tags'], $text['tags']);
                        unset($text['tags']);
                    }

                    $data['extra'] = $text;
                } else {
                    $data['message'] = (string) $text;
                }

                if ($this->context) {
                    $data['extra']['context'] = parent::getContextMessage();
                }

                $data = $this->runExtraCallback($text, $data);

                $scope->setUser($data['userData'], true);
                foreach ($data['extra'] as $key => $value) {
                    $scope->setExtra((string) $key, $value);
                }
                foreach ($data['tags'] as $key => $value) {
                    if ($value) {
                        $scope->setTag($key, $value);
                    }
                }

                if ($text instanceof \Throwable) {
                    Sentry\captureException($text);
                } else {
                    Sentry\captureMessage($data['message'], $this->getLogLevel($level));
                }
            });
        }
    }


    public function runExtraCallback($text, $data)
    {
        if (is_callable($this->extraCallback)) {
            $data['extra'] = call_user_func($this->extraCallback, $text, $data['extra'] ?? []);
        }

        return $data;
    }

    protected function getLogLevel($level): Severity
    {
        switch ($level) {
            case Logger::LEVEL_PROFILE:
            case Logger::LEVEL_PROFILE_BEGIN:
            case Logger::LEVEL_PROFILE_END:
            case Logger::LEVEL_TRACE:
                return Severity::debug();
            case Logger::LEVEL_WARNING:
                return Severity::warning();
            case Logger::LEVEL_ERROR:
                return Severity::error();
            case Logger::LEVEL_INFO:
            default:
                return Severity::info();
        }
    }

    /**
     * Returns the text display of the specified level for the Sentry.
     *
     * @param integer $level The message level, e.g. [[LEVEL_ERROR]], [[LEVEL_WARNING]].
     * @return string
     */
    public static function getLevelName($level)
    {
        static $levels = [
            Logger::LEVEL_ERROR => 'error',
            Logger::LEVEL_WARNING => 'warning',
            Logger::LEVEL_INFO => 'info',
            Logger::LEVEL_TRACE => 'debug',
            Logger::LEVEL_PROFILE_BEGIN => 'debug',
            Logger::LEVEL_PROFILE_END => 'debug',
        ];

        return $levels[$level] ?? 'error';
    }
}