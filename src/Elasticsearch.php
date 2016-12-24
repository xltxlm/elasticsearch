<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 19:02
 */

namespace xltxlm\elasticsearch;

use xltxlm\elasticsearch\Unit\ElasticsearchConfig;


/**
 * Class Elasticsearch
 * @package xltxlm\elasticsearch
 */
abstract class Elasticsearch
{
    /** @var  ElasticsearchConfig */
    protected $elasticsearchConfig;
    /** @var string id,也就是数据库的自增id */
    protected $id = '';

    /**
     * @return ElasticsearchConfig
     */
    final public function getElasticsearchConfig(): ElasticsearchConfig
    {
        return $this->elasticsearchConfig;
    }

    /**
     * @param ElasticsearchConfig $elasticsearchConfig
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