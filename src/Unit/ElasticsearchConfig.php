<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 16:49.
 */

namespace xltxlm\elasticsearch\Unit;

use xltxlm\config\TestConfig;
use xltxlm\elasticsearch\ElasticsearchQuery;
use xltxlm\page\PageObject;

/**
 * 检索数据库模型
 * Class ElasticsearchModel.
 */
abstract class ElasticsearchConfig implements TestConfig
{
    /** @var string 服务器ip地址 */
    protected $host = '127.0.0.1';
    /** @var int 服务器端口 */
    protected $port = 9200;

    /** @var string 索引,也就是数据库名称 */
    protected $index = '';
    /** @var string 类型,也就是表 */
    protected $type = '';
    /** @var string 开启http认账的账户 */
    protected $user = '';
    /** @var string 开启http认证的密码 */
    protected $pass = '';

    /** @var int 超时设置 */
    protected $timeout = 1;

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     *
     * @return ElasticsearchConfig
     */
    public function setUser(string $user): ElasticsearchConfig
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     *
     * @return ElasticsearchConfig
     */
    public function setPass(string $pass): ElasticsearchConfig
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     *
     * @return ElasticsearchConfig
     */
    public function setPort(int $port): ElasticsearchConfig
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return ElasticsearchConfig
     */
    public function setHost(string $host): ElasticsearchConfig
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return int
     */
    final public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     *
     * @return ElasticsearchConfig
     */
    final public function setTimeout(int $timeout): ElasticsearchConfig
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @return string
     */
    final public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @param string $index
     *
     * @return ElasticsearchConfig
     */
    final public function setIndex(string $index): ElasticsearchConfig
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @return string
     */
    final public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return ElasticsearchConfig
     */
    final public function setType(string $type): ElasticsearchConfig
    {
        $this->type = $type;

        return $this;
    }

    /**
     * 测试服务是否正常
     * @return array
     */
    public function test()
    {
        $pageObject = new PageObject();
        return (new ElasticsearchQuery())
            ->setElasticsearchConfig(new static())
            ->setClassName(\stdClass::class)
            ->setPageObject($pageObject)
            ->setBodyString("")
            ->__invoke();
    }

    /**
     * 返回连接的数据配置.
     *
     * @return array
     */
    final public function __invoke()
    {
        return [
            'index' => $this->getIndex(),
            'type' => $this->getType(),
            'client' => [
                'timeout' => $this->getTimeout(),        // ten second timeout
                'connect_timeout' => $this->getTimeout(),
            ],
        ];
    }
}
