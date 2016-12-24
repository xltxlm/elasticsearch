<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 19:35.
 */

namespace xltxlm\elasticsearch;

use Elasticsearch\ClientBuilder;
use xltxlm\orm\PageObject;

/**
 * Class ElasticsearchSearch.
 */
class ElasticsearchSearch extends Elasticsearch
{
    /** @var mixed 返回结果的模型类 */
    protected $bodyObject;
    /** @var array 倒序排列的字段名称 */
    protected $OrderByDesc = [];
    /** @var array 正向排列的字段名称 */
    protected $OrderByAsc = [];
    /** @var PageObject */
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
     * @return ElasticsearchSearch
     */
    public function setPageObject(PageObject $pageObject): ElasticsearchSearch
    {
        $this->pageObject = $pageObject;

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
     * @param string $OrderByAsc
     *
     * @return ElasticsearchSearch
     */
    public function setOrderByAsc(string $OrderByAsc): ElasticsearchSearch
    {
        $this->OrderByAsc[] = $OrderByAsc;

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
     * @param string $OrderByDesc
     *
     * @return ElasticsearchSearch
     */
    public function setOrderByDesc(string $OrderByDesc): ElasticsearchSearch
    {
        $this->OrderByDesc[] = $OrderByDesc;

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
     * @return ElasticsearchSearch
     */
    public function setBodyObject($bodyObject)
    {
        $this->bodyObject = $bodyObject;

        return $this;
    }

    /**
     * 返回查询对象的结果集.
     *
     * @return array
     */
    public function __invoke()
    {
        $ReflectionClass = (new \ReflectionClass($this->getBodyObject()));
        //取出公开的getxx方法,然后用ana链接进行查询
        $Methods = $ReflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        $searchs = [];
        foreach ($Methods as $method) {
            if (strpos($method->name, 'get') === 0) {
                $data = call_user_func([$this->getBodyObject(), $method->name]);
                if (!empty($data)) {
                    $name = lcfirst(substr($method->name, 3));
                    $searchs[] = ['match' => [$name => $data]];
                }
            }
        }
        $OrderByDescAscs = [];
        foreach ($this->getOrderByDesc() as $item) {
            $OrderByDescAscs[] = [$item.'.keyword' => ['order' => 'desc']];
        }
        foreach ($this->getOrderByAsc() as $item) {
            $OrderByDescAscs[] = [$item.'.keyword' => ['order' => 'asc']];
        }
        $index = $this->getElasticsearchConfig()->__invoke() +
            [
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => $searchs,
                        ],
                    ],
                    'sort' => $OrderByDescAscs,
                ],
            ];
        //分页
        if (isset($this->pageObject)) {
            $index += [
                'from' => $this->getPageObject()->getFrom(),
                'size' => $this->getPageObject()->getPrepage()
            ];
        }
        $client = ClientBuilder::create()->build();

        $response = $client->search($index);
        $BodyObjects = [];
        foreach ($response['hits']['hits'] as $hit) {
            $BodyObjects[] = (new \ReflectionClass($this->getBodyObject()))
                ->newInstance($hit['_source']);
        }

        return $BodyObjects;
    }
}
