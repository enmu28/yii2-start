<?php
/**
 * @author quocdaijr
 * @time 19/05/2020
 * @package common\components\elasticsearch
 * @version 1.0
 * Require Elasticsearch > 7.0
 * Abstract class has references from package
 * "kuroneko/yii2-elastic-search" (Code by Giang on site git.tuoitre.com.vn)
 */

namespace common\components\elasticsearch;

use common\components\traits\LogTrait;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

abstract class AbstractElasticsearch
{
    use LogTrait;

    /**
     * @var array|bool|false|string|null
     */
    private $server;

    /**
     * @var Client
     */
    private $currentConnection;

    /**
     * @var
     */
    private $timeout;

    public $_id = 'id';

    /**
     * BaseElasticSearchAbstract constructor.
     * @param $server
     * @param string $timeout
     */
    public function __construct($server, $timeout = '1s')
    {
        try {
            if (empty($server))
                throw new \Exception('Please provide elastic server config');

            $this->server = $server;
            $this->timeout = $timeout;

            if (empty($this->index()))
                throw new \Exception('Index is invalid, please check this.');

            if (!empty($this->server)) {
                $this->currentConnection = ClientBuilder::create()
                    ->setHosts([$this->server])->build();
            }

            if ($this->currentConnection && $this->isConnected() && !$this->exists() && !empty($this->map())) {
                $this->mapping();
            }
        } catch (\Exception $exception) {
            $this->printException($exception);
            $this->writeLog($exception);
        }
    }

    public abstract function index(): string;

    public abstract function map(): array;

    public function settings()
    {
        return [];
    }

    private function generateId($length = 50)
    {
        $str = '1234567890qazwsxedcrfvtgbyhnujmiklopQAZWSXEDCRFVTGBYHNUJMIKLOP';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $str[rand(0, ($length - 1))];
        }
        return $result;
    }

    /**
     * @return Client|ClientBuilder
     */
    public function getConnection()
    {
        return $this->currentConnection;
    }

    public function isConnected()
    {
        if (empty($this->currentConnection)) return false;
        return empty($this->currentConnection) !== true || $this->currentConnection->ping() !== false;
    }

    public function exists()
    {
        if ($this->isConnected())
            return $this->getConnection()
                ->indices()
                ->exists(['index' => $this->index()]);
        else
            return false;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    private function checkPrerequisiteBeforeExecute()
    {
        return $this->isConnected() && $this->exists();
    }

    /**
     * @return bool
     */
    public function mapping()
    {
        try {
            if ($this->isConnected()) {
                $params = [
                    'index' => $this->index(),
                    'body' => [
                        'mappings' => [
                            '_source' => [
                                'enabled' => true
                            ],
                            'properties' => $this->map()
                        ]
                    ]
                ];
                if (!empty($this->settings())) {
                    $params['body']['settings'] = $this->settings();
                }
                $this->getConnection()->indices()->create($params);
            } else {
                return false;
            }
        } catch (\Exception $exception) {
            $this->printException($exception);
            $this->writeLog($exception);
            return false;
        }
    }

    public function insert($data, $refresh = true)
    {
        try {
            if ($this->isConnected()) {
                $params = [
                    'index' => $this->index(),
                    'timeout' => $this->getTimeout(),
                    'body' => $data
                ];

                if (!empty($data[$this->_id]))
                    $params['id'] = $data[$this->_id];

                if ($refresh === true) {
                    $params['refresh'] = $refresh;
                }
                $result = $this->getConnection()->index($params);
                if (isConsole())
                    return $result;

                return !empty($result) && !empty($result['_shards']) && isset($result['_shards']['successful']) && $result['_shards']['successful'] == 1;

            } else {
                return false;
            }
        } catch (\Exception $exception) {
            $this->printException($exception);
            $this->writeLog($exception);
            return false;
        }
    }

    public function update($data, $refresh = true)
    {
        try {
            if ($this->isConnected() && !empty($data[$this->_id])) {
                $params = [
                    'index' => $this->index(),
                    'id' => $data[$this->_id],
                ];

                if ($this->getConnection()->exists($params)) {
                    $params['body'] = [
                        'doc' => $data
                    ];
                    if ($refresh === true)
                        $params['refresh'] = $refresh;

                    $params['timeout'] = $this->getTimeout();

                    $result = $this->getConnection()->update($params);
                    if (isConsole())
                        return $result;
                    return ($result['result'] === 'updated' && $result['_shards']['successful'] == 1) || $result['result'] === 'noop';
                }
            }
            return false;
        } catch (\Exception $exception) {
            $this->printException($exception);
            $this->writeLog($exception);
            return false;
        }
    }

    public function delete($data, $refresh = true)
    {
        try {
            if ($this->isConnected()) {
                $params = [
                    'index' => $this->index(),
                ];

                if ($refresh === true)
                    $params['refresh'] = $refresh;

                if (!empty($data[$this->_id])) {
                    $params['id'] = $data[$this->_id];
                    if ($this->getConnection()->exists($params)) {
                        $params['timeout'] = $this->getTimeout();
                        $result = $this->getConnection()->delete($params);
                    } else {
                        $result = isConsole() ? "Không có dữ liệu cần xóa" : false;
                    }
                } else if (!empty($data['body'])) {
                    $params['body'] = $data['body'];
                    $params['conflicts'] = 'proceed';
                    $result = $this->getConnection()->deleteByQuery($params);

                } else {
                    $result = isConsole() ? "Tham số truyền vào không phù hợp" : false;
                }
                if (isConsole())
                    return $result;
                return !empty($result) && isset($result['deleted']) && $result['deleted'] != 0;
            } else {
                return false;
            }
        } catch (\Exception $exception) {
            $this->printException($exception);
            $this->writeLog($exception);
            return false;
        }
    }

    public function search($params)
    {
        $data = [];
        try {
            if ($this->checkPrerequisiteBeforeExecute()) {
                if (empty($params['index']))
                    $params['index'] = $this->index();
                if (!isset($params['body']['from']) && !isset($params['body']['size'])) {
                    $params['body']['size'] =  $this->count($params);;
                    $params['body']['from'] = 0;
                }

                $data = $this->getConnection()->search($params);
            }

            $result = [];
            $total = !empty($data['hits']) && !empty($data['hits']['total']) && !empty($data['hits']['total']['value']) ? $data['hits']['total']['value'] : 0;
            if (!empty($data['hits']['hits'])) {
                $hits = $data['hits']['hits'];
                $step = 0;

                while ($step < count($hits)) {
                    if (!empty($hits[$step]['_source'])) {
                        $result[] = $hits[$step]['_source'];
                    }
                    $step++;
                }
            }

            return [
                'data' => $result,
                'total' => $total
            ];
        } catch (\Exception $exception) {
            $this->printException($exception);
            $this->writeLog($exception);
            return $data;
        }
    }

    public function count($params)
    {
        $count = 0;
        try {
            if ($this->checkPrerequisiteBeforeExecute()) {
                if (empty($params['index']))
                    $params['index'] = $this->index();

                if (isset($params['size']))
                    unset($params['size']);

                if (isset($params['from']))
                    unset($params['from']);

                if (isset($params['body']['sort']))
                    unset($params['body']['sort']);

                $count_data = $this->getConnection()->count($params);
                if (isset($count_data['count']))
                    $count = $count_data['count'];
            }
            return $count;
        } catch (\Exception $exception) {
            $this->printException($exception);
            $this->writeLog($exception);
            return $count;
        }
    }
}