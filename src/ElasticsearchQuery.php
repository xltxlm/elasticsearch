<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/25
 * Time: 22:33.
 */

namespace xltxlm\elasticsearch;

use Elasticsearch\ClientBuilder;
use xltxlm\page\PageObject;

/**
 * 完全自定义查询
 * Class ElasticsearchQuery.
 */
class ElasticsearchQuery extends Elasticsearch
{
    /** @var string 返回结果的模型类 */
    protected $bodyString;
    /** @var string 返回的对象类型 */
    protected $className = "";
    /** @var array 倒序排列的字段名称 */
    protected $OrderByDesc = [];
    /** @var array 正向排列的字段名称 */
    protected $OrderByAsc = [];
    /** @var PageObject 分页 */
    protected $pageObject;

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return ElasticsearchQuery
     */
    public function setClassName(string $className): ElasticsearchQuery
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return PageObject
     */
    public function getPageObject(): PageObject
    {
        return $this->pageObject;
    }

    /**
     * @param PageObject $pageObject
     *
     * @return ElasticsearchQuery
     */
    public function setPageObject(PageObject &$pageObject): ElasticsearchQuery
    {
        $this->pageObject = $pageObject;

        return $this;
    }

    /**
     * @return string
     */
    public function getBodyString()
    {
        return $this->bodyString;
    }

    /**
     * @param string $bodyString
     *
     * @return ElasticsearchQuery
     */
    public function setBodyString($bodyString)
    {
        $this->bodyString = $bodyString;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrderByDesc(): array
    {
        return $this->OrderByDesc;
    }

    /**
     * @param array $OrderByDesc
     *
     * @return ElasticsearchQuery
     */
    public function setOrderByDesc(array $OrderByDesc): ElasticsearchQuery
    {
        $this->OrderByDesc = $OrderByDesc;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrderByAsc(): array
    {
        return $this->OrderByAsc;
    }

    /**
     * @param array $OrderByAsc
     *
     * @return ElasticsearchQuery
     */
    public function setOrderByAsc(array $OrderByAsc): ElasticsearchQuery
    {
        $this->OrderByAsc = $OrderByAsc;

        return $this;
    }

    public function __invoke()
    {
        $index = $this->getElasticsearchConfig()->__invoke() +
            [
                'body' => $this->getBodyString(),
            ];

        //分页
        if (isset($this->pageObject)) {
            $index += [
                'from' => $this->getPageObject()->getFrom(),
                'size' => $this->getPageObject()->getPrepage(),
            ];
        }
        $response = $this->getClient()->search($index);
        $BodyObjects = [];
        foreach ($response['hits']['hits'] as $hit) {
            if ($this->getClassName() == \stdClass::class) {
                $stdClass = new \stdClass;
                foreach ($hit['_source'] as $key => $item) {
                    $stdClass->$key = $item;
                }
                if ($hit['highlight']) {
                    foreach ($hit['highlight'] as $key1 => $item1) {
                        $item1 = current($item1);
                        $stdClass->$key1 = $item1;
                    }
                }
                $BodyObjects[] = $stdClass;
            } else {
                //通过反射的方式加载内容:注意,不会调用内部的 get/set 对来处理内容
                $newInstance = (new \ReflectionClass($this->getClassName()))
                    ->newInstance();
                foreach ($hit['_source'] as $key => $item) {
                    try {
                        $ReflectionProperty = (new \ReflectionProperty($newInstance, $key));
                        $ReflectionProperty->setAccessible(true);
                        $ReflectionProperty->setValue($newInstance, $item);
                    } catch (\Exception $e) {
                        $newInstance->$key = $item;
                    }
                    if ($hit['highlight']) {
                        foreach ($hit['highlight'] as $key1 => $item1) {
                            $item1 = current($item1);
                            try {
                                $ReflectionProperty = (new \ReflectionProperty($newInstance, $key1));
                                $ReflectionProperty->setAccessible(true);
                                $ReflectionProperty->setValue($newInstance, $item1);
                            } catch (\Exception $e) {
                                $newInstance->$key1 = $item1;
                            }
                        }
                    }
                }
                $BodyObjects[] = $newInstance;
            }
        }
        $this->pageObject->setTotal($response['hits']['total'])
            ->__invoke();
        return $BodyObjects;
    }
}
