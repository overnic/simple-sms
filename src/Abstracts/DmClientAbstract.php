<?php
/**
 * Created by PhpStorm.
 * User: overnic
 * Date: 2018/1/3
 * Time: 16:58
 */
namespace OverNick\Dm\Abstracts;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use OverNick\Dm\Config\ResultConfig;
use OverNick\Dm\Exceptions\BadResultException;
use InvalidArgumentException;

/**
 * Class DmClientAbstract
 * @package OverNick\Dm\Abstracts
 */
abstract class DmClientAbstract
{
    /**
     * @var array 配置信息
     */
    protected $config;

    /**
     * guzzle http 的client
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var array 传入的参数
     */
    protected $params;

    /**
     * 返回的对象
     *
     * @var ResultConfig
     */
    protected $result;

    public function __construct(array $config = [],Client $client = null)
    {
        $this->config = $this->getConfig($config);

        $this->client = is_null($client) ? new Client() : $client;

        $this->result = new ResultConfig();
    }

    /**
     * 发送短信
     *
     * @param DmConfigAbstract $params
     * @return mixed
     * @throws BadResultException
     */
    public function send(DmConfigAbstract $params)
    {
        if(!isset($params['to'])){
            throw new InvalidArgumentException("params is empty.");
        }

        $this->params = $this->getParams($params);

        // 批量发送还是单条发送
        $result =  is_array($params['to']) ? $this->sendMulti() : $this->sendOnce();

        // 必须按照约定返回对象
        if (!$result instanceof ResultConfig) {
            // throw new BadResultException();
        }

        return $result;
    }

    /**
     * @param $config
     * @return mixed
     */
    abstract protected function getConfig($config);

    /**
     * 校验传入参数
     *
     * @param DmConfigAbstract $params
     * @return mixed
     */
    abstract protected function getParams(DmConfigAbstract $params);

    /**
     * 单发
     *
     * @return mixed
     */
    abstract protected function sendOnce();

    /**
     * 群发
     *
     * @return mixed
     */
    abstract protected function sendMulti();
}