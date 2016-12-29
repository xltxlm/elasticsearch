<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/25
 * Time: 22:33.
 */

namespace xltxlm\elasticsearch;

use Elasticsearch\ClientBuilder;
use xltxlm\orm\PageObject;

/**
 * 完全自定义查询
 * Class ElasticsearchQuery.
 */
class ElasticsearchQuery extends Elasticsearch
{
    /** @var mixed 返回结果的模型类 */
    protected $bodyObject;
    /** @var array 倒序排列的字段名称 */
    protected $OrderByDesc = [];
    /** @var array 正向排列的字段名称 */
    protected $OrderByAsc = [];

    /** @var PageObject 分页 */
    protected $pageObject;

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
     * @return mixed
     */
    public function getBodyObject()
    {
        return $this->bodyObject;
    }

    /**
     * @param mixed $bodyObject
     *
     * @return ElasticsearchQuery
     */
    public function setBodyObject($bodyObject)
    {
        $this->bodyObject = $bodyObject;

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
                'body' => [],
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
            $BodyObjects[] = (new \ReflectionClass($this->getBodyObject()))
                ->newInstance($hit['_source']);
        }
        $this->pageObject->setTotal($response['hits']['total']);

        return $BodyObjects;
    }
}
