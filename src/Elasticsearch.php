<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 19:02.
 */

namespace xltxlm\elasticsearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use xltxlm\logger\Operation\Connect\ElasticsearchConnectLog;
use xltxlm\elasticsearch\Unit\ElasticsearchConfig;

/**
 * Class Elasticsearch.
 */
abstract class Elasticsearch
{

    public const DAY = 'day';
    public const HOUR = 'hour';
    public const MINUTE = 'minute';
    public const SECOND = 'second';

    /** @var ElasticsearchConfig */
    protected $elasticsearchConfig;
    /** @var string id,也就是数据库的自增id */
    protected $id = '';
    /** @var Client */
    protected static $client = [];

    /**
     * @return Client
     */
    public function getClient()
    {
        //如果主机配置是数组，那么拆分开
        if ($this->getElasticsearchConfig()->getHost()[0] == '[') {
            $hosts = [];
            foreach (json_decode($this->getElasticsearchConfig()->getHost(), true) as $host) {
                $hosts[] = [
                    'scheme' => 'http',
                    'host' => $host,
                    'port' => $this->getElasticsearchConfig()->getPort(),
                    'user' => $this->getElasticsearchConfig()->getUser(),
                    'pass' => $this->getElasticsearchConfig()->getPass(),
                ];
            }
        } else {
            $hosts = [[
                'scheme' => 'http',
                'host' => $this->getElasticsearchConfig()->getHost(),
                'port' => $this->getElasticsearchConfig()->getPort(),
                'user' => $this->getElasticsearchConfig()->getUser(),
                'pass' => $this->getElasticsearchConfig()->getPass(),
            ]];
        }
        //新开进程的话，进行新的链接回话
        $configSign = md5(json_encode($hosts, JSON_UNESCAPED_UNICODE)).posix_getpid();
        if (empty(self::$client[$configSign])) {
            self::$client[$configSign] = ClientBuilder::create()
                ->setHosts($hosts)
                ->build();
            //记录日志
            (new ElasticsearchConnectLog())
                ->setElasticsearchConfig($this->getElasticsearchConfig())
                ->__invoke();
        }

        return self::$client[$configSign];
    }

    /**
     * @return ElasticsearchConfig
     */
    final public function getElasticsearchConfig(): ElasticsearchConfig
    {
        return $this->elasticsearchConfig;
    }

    /**
     * @param ElasticsearchConfig $elasticsearchConfig
     *
     * @return $this
     */
    final public function setElasticsearchConfig(ElasticsearchConfig $elasticsearchConfig)
    {
        $this->elasticsearchConfig = $elasticsearchConfig;

        return $this;
    }

    /**
     * @return string
     */
    final public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    final public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }
}
