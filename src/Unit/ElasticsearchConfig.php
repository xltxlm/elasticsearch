<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 16:49.
 */

namespace xltxlm\elasticsearch\Unit;

/**
 * 检索数据库模型
 * Class ElasticsearchModel
 * @package xltxlm\elasticsearch\Unit
 */
abstract class ElasticsearchConfig
{
    /** @var string 索引,也就是数据库名称 */
    protected $index = '';
    /** @var string 类型,也就是表 */
    protected $type = '';


    /** @var int 超时设置 */
    protected $timeout = 1;

    /**
     * @return int
     */
    final public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
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
     * 返回连接的数据配置
     * @return array
     */
    final public function __invoke()
    {
        return [
            'index' => $this->getIndex(),
            'type' => $this->getType(),
            'client' => [
                'timeout' => $this->getTimeout(),        // ten second timeout
                'connect_timeout' => $this->getTimeout()
            ]
        ];
    }
}
