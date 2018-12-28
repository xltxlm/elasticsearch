<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/25
 * Time: 22:33.
 */

namespace xltxlm\elasticsearch;

use Psr\Log\LogLevel;
use xltxlm\elk\vendor\xltxlm\elasticsearch\src\Unit\EggDayModel;
use xltxlm\elk\vendor\xltxlm\elasticsearch\src\Unit\EggName2DayModel;
use xltxlm\elk\vendor\xltxlm\elasticsearch\src\Unit\EggNameModel;
use xltxlm\helper\Hclass\ConvertObject;
use xltxlm\helper\Util;
use xltxlm\logger\Operation\Action\ElasticsearchRunLog;
use xltxlm\page\PageObject;

/**
 * 完全自定义查询
 * Class ElasticsearchQuery.
 */
class ElasticsearchQuery extends Elasticsearch
{
    /** @var eggNameModel[] */
    protected $eggNameModels = [];
    /** @var eggDayModel[] */
    protected $eggDayModels = [];
    /** @var array EggName2DayModel */
    protected $EggName2DayModels = [];
    /** @var string 返回结果的模型类 */
    protected $bodyString;
    /** @var string 返回的对象类型 */
    protected $className = \stdClass::class;
    /** @var PageObject 分页 */
    protected $pageObject;
    /** @var int 是否是统计分析数据 */
    protected $egg = 0;
    /** @var bool 是否将结果转换成一维数组 */
    protected $to1Array = false;
    /** @var bool 是否输出查询信息供调试 */
    protected $debug = false;

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     * @return ElasticsearchQuery
     */
    public function setDebug(bool $debug): ElasticsearchQuery
    {
        $this->debug = $debug;
        return $this;
    }


    /**
     * @return bool
     */
    public function isTo1Array(): bool
    {
        return $this->to1Array;
    }

    /**
     * @param bool $to1Array
     * @return ElasticsearchQuery
     */
    public function setTo1Array(bool $to1Array): ElasticsearchQuery
    {
        $this->to1Array = $to1Array;
        return $this;
    }

    /**
     * @return int
     */
    public function getEgg(): int
    {
        return $this->egg;
    }

    /**
     * @param int $egg
     * @return ElasticsearchQuery
     */
    public function setEgg(int $egg): ElasticsearchQuery
    {
        $this->egg = $egg;
        return $this;
    }


    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     *
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
     * @return eggNameModel[]
     */
    public function getEggNameModels(): array
    {
        return $this->eggNameModels;
    }

    /**
     * @return EggDayModel[]
     */
    public function getEggDayModels(): array
    {
        return $this->eggDayModels;
    }

    /**
     * @return EggName2DayModel[]
     */
    public function getEggName2DayModels(): array
    {
        return $this->EggName2DayModels;
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
        $elasticsearchRunLog = (new ElasticsearchRunLog($this->getElasticsearchConfig()))
            ->setElasticsearchQueryString($index);

        if ($this->isDebug()) {
            Util::d([$index]);
        }
        try {
            $response = $this->getClient()->search($index);
            if ($this->pageObject) {
                //获取原来的最大分页数目
                $from = $this->getPageObject()->getFrom();

                $this->pageObject
                    ->setTotal($response['hits']['total'])
                    ->__invoke();
                if ($from > $this->pageObject->getFrom()) {
                    //分页的位置超出范围了，再查一次
                    $index = array_merge($index, [
                        'from' => $this->getPageObject()->getFrom(),
                        'size' => $this->getPageObject()->getPrepage(),
                    ]);
                    if ($this->isDebug()) {
                        Util::d($index);
                    }
                    $response = $this->getClient()->search($index);
                    $this->pageObject
                        ->setTotal($response['hits']['total'])
                        ->__invoke();
                }
            }
            if ($this->getEgg()) {
                return $this->egg($response);
            } else {
                return $this->monal($response);
            }
        } catch (\Exception $e) {
            Util::d([$e->getMessage(), $this->getElasticsearchConfig()]);
            $elasticsearchRunLog
                ->setMessageDescribe($e->getMessage())
                ->setType(LogLevel::ERROR);
            throw $e;
        } finally {
            $elasticsearchRunLog
                ->__invoke();
        }
        return null;
    }

    private function egg($response)
    {

        if ($this->getEgg() == 1) {
            $meta = current($response['aggregations'])['meta']['field'];
            $buckets = current($response['aggregations'])['buckets'];
            $return = [];
            foreach ($buckets as $bucket) {
                $return[] = [
                    $meta => $bucket['key_as_string'] ?: $bucket['key'],
                    '_num' => strval(isset($bucket['doc_count']['value']) ?$bucket['doc_count']['value']:$bucket['doc_count'])
                ];
            }
            return $return;
        } elseif ($this->getEgg() == 2) {
            $meta = current($response['aggregations'])['meta']['field'];
            $buckets = current($response['aggregations'])['buckets'];
            $return = [];
            foreach ($buckets as $bucket) {

                $meta2 = $bucket['groupby_2']['meta']['field'];
                $bucket2s = $bucket['groupby_2']['buckets'];

                foreach ($bucket2s as $bucket2) {
                    $return[] = [
                        $meta => $bucket['key_as_string'] ?: $bucket['key'],
                        $meta2 => $bucket2['key_as_string'] ?: $bucket2['key'],
                        '_num' => strval(isset($bucket2['doc_count']['value']) ?$bucket2['doc_count']['value']:$bucket2['doc_count'])
                    ];
                }
            }

            return $return;
        } elseif ($this->getEgg() == 100) {
            //没有任何分组条件,只是纯粹的计数而已
            $return = [];
            $return[] =
                [
                    '_num' => $response['hits']['total']
                ];
            return $return;
        }
    }

    /**
     * 常规的查询
     */
    private function monal($response)
    {
        $BodyObjects = [];
        foreach ($response['hits']['hits'] as $hit) {
            if ($this->getClassName() == \stdClass::class) {
                $stdClass = new \stdClass();
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

        if ($this->isTo1Array()) {
            foreach ($BodyObjects as &$bodyObject) {
                if ($this->getClassName() != \stdClass::class) {
                    $bodyObject = (new ConvertObject)
                        ->setObject($bodyObject)
                        ->toArray();
                } else {
                    $bodyObject = get_object_vars($bodyObject);
                }
            }
        }
        return $BodyObjects;
    }
}
