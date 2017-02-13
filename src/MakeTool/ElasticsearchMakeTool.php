<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/1/13
 * Time: 11:03.
 */

namespace xltxlm\elasticsearch\MakeTool;

use xltxlm\elasticsearch\Unit\ElasticsearchConfig;

/**
 * 根据配置生成可执行的类
 * Class ElasticsearchMakeTool.
 */
class ElasticsearchMakeTool
{
    /** @var ElasticsearchConfig */
    protected $ElasticsearchConfig;

    /** @var array 需要转换的类 */
    protected $classNames = [];

    /**
     * @return ElasticsearchConfig
     */
    public function getElasticsearchConfig(): ElasticsearchConfig
    {
        return $this->ElasticsearchConfig;
    }

    /**
     * @param ElasticsearchConfig $ElasticsearchConfig
     *
     * @return ElasticsearchMakeTool
     */
    public function setElasticsearchConfig(ElasticsearchConfig $ElasticsearchConfig): ElasticsearchMakeTool
    {
        $this->ElasticsearchConfig = $ElasticsearchConfig;

        return $this;
    }

    /**
     * @return array
     */
    public function getClassNames(): array
    {
        return $this->classNames;
    }

    /**
     * @param string $classNames
     *
     * @return ElasticsearchMakeTool
     */
    public function setClassNames(string $classNames): ElasticsearchMakeTool
    {
        $this->classNames[] = $classNames;

        return $this;
    }

    public function __invoke()
    {
        foreach ($this->classNames as $className) {
            $ReflectionClass = (new \ReflectionClass($className));
            (new ElasticsearchMakeToolUnit())
                ->setClassName($className)
                ->setClassShortName($ReflectionClass->getShortName())
                ->setProperties($ReflectionClass->getProperties())
                ->setNameSpace($ReflectionClass->getNamespaceName())
                ->setFilePath($ReflectionClass->getFileName())
                ->setElasticsearchMakeTool($this)
                ->__invoke();
        }
    }
}
