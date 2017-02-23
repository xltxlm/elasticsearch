<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/25
 * Time: 22:33.
 */

namespace xltxlm\elasticsearch;

use xltxlm\elasticsearch\Logger\ElasticsearchRunLog;
use xltxlm\elk\vendor\xltxlm\elasticsearch\src\Unit\EggDayModel;
use xltxlm\elk\vendor\xltxlm\elasticsearch\src\Unit\EggName2DayModel;
use xltxlm\elk\vendor\xltxlm\elasticsearch\src\Unit\EggNameModel;
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
    /** @var bool 是否是统计分析数据 */
    protected $egg = false;

    /**
     * @return bool
     */
    public function isEgg(): bool
    {
        return $this->egg;
    }

    /**
     * @param bool $egg
     * @return ElasticsearchQuery
     */
    public function setEgg(bool $egg): ElasticsearchQuery
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
        (new ElasticsearchRunLog())
            ->setQueryString($index)
            ->__invoke();
        $response = $this->getClient()->search($index);
        if ($this->isEgg()) {
            $this->egg($response);
            return $this->getEggNameModels();
        } else {
            return $this->monal($response);
        }
    }

    private function egg($response)
    {
        $buckets = $response['aggregations']['all_interests']['buckets'];
        foreach ($buckets as $item) {
            $name = $item['key'];
            $this->eggNameModels[] = (new EggNameModel)
                ->setName($name);

            foreach ($item['daysbuckets']['buckets'] as $daysbucket) {
                $day = $daysbucket['key_as_string'];
                $this->eggDayModels[$day] = $this->eggDayModels[$day]??(new EggDayModel)
                        ->setDay($day);

                $this->EggName2DayModels[$name.$day] = (new EggName2DayModel())
                    ->setName($name)
                    ->setDay($day)
                    ->setNum($daysbucket['doc_count']);
            }
        }
        //日期进行排序整理
        krsort($this->eggDayModels);
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
        $this->pageObject->setTotal($response['hits']['total'])
            ->__invoke();
        return $BodyObjects;
    }
}
