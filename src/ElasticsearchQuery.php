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
                }
                $BodyObjects[] = $newInstance;
            }
        }
        $this->pageObject->setTotal($response['hits']['total'])
            ->__invoke();
        return $BodyObjects;
    }
}
