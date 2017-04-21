<?php /** @var \xltxlm\elasticsearch\MakeTool\ElasticsearchMakeToolUnit $this */?>
<<?= '?' ?>php

namespace <?=$this->getNameSpace()?>\<?=(new \ReflectionClass($this->getElasticsearchMakeTool()->getElasticsearchConfig()))->getShortName()?>;

use \xltxlm\elasticsearch\Elasticsearch;
use xltxlm\elasticsearch\ElasticsearchQuery;
use \xltxlm\elasticsearch\Unit\ElasticsearchAction;
use <?=(new \ReflectionClass($this->getElasticsearchMakeTool()->getElasticsearchConfig()))->getName()?>;

final class <?=$this->getClassShortName()?>ElasticsearchEgg
{

    /** @var string 要分组的字段 */
    protected $eggField = "";
    /** @var string 分组的日期字段 */
    protected $eggDayField = "";
    /** @var string x轴时间纬度,默认是按天 */
    protected $eggDayType = Elasticsearch::DAY;



    /**
     * @return string
     */
    public function getEggDayField(): string
    {
        return $this->eggDayField;
    }

    /**
     * @param string $eggDayField
     * @return static
     */
    public function setEggDayField(string $eggDayField)
    {
        $this->eggDayField = $eggDayField;
        return $this;
    }
    /**
     * @return string
     */
    public function getEggField(): string
    {
        return $this->eggField;
    }

    /**
     * @param string $eggField
     * @return static
     */
    public function setEggField(string $eggField)
    {
        $this->eggField = $eggField;
        return $this;
    }

    /**
     * @return string
     */
    public function getEggDayType(): string
    {
        return $this->eggDayType;
    }

    /**
     * @param string $eggDayType
     * @return static
     */
    public function setEggDayType(string $eggDayType)
    {
        $this->eggDayType = $eggDayType;
        return $this;
    }

    /**
     * @return \<?=$this->getClassName()?>[]
     */
    public function __invoke()
    {
        $query = $queryNotIn = [];
        if ($this->__ranges) {
            foreach ($this->__ranges as $field => $bind) {
                $query[] = $bind;
            }
        }
        $sort = '';
        if ($this->__orderby) {
            $sort = ' ,"sort": [ '.join(",", $this->__orderby).' ] ';
        }

        if (!empty($this->__string)) {
            $query[] = sprintf('{"query_string": { "query": "%s"} }', $this->__string);
        }
        foreach ($this->__binds as $field => $bind) {
            $query[] = sprintf('{ "%s":{ "%s":"%s" } }', $bind['action'], $field, addslashes($bind['string']));
        }
        foreach ($this->__notin as $field => $bind) {
            $queryNotIn[] = $bind;
        }

        $format="yyyy-MM-dd";
        if($this->getEggDayType() == Elasticsearch::DAY)
        {
            $format="yyyy-MM-dd";
        }elseif($this->getEggDayType() == Elasticsearch::HOUR)
        {
            $format="yyyy-MM-dd HH";
        }elseif($this->getEggDayType() == Elasticsearch::MINUTE)
        {
            $format="yyyy-MM-dd HH:mm";
        }elseif($this->getEggDayType() == Elasticsearch::SECOND)
        {
            $format="yyyy-MM-dd HH:mm:ss";
        }

        $date_histogram='
                "field": "'.$this->getEggDayField().'",
                "format": "'.$format.'",
                "interval": "'.$this->getEggDayType().'",
                "time_zone": "+08:00"
        ';

        $bodyString = '{
                "size":0,
                "query": {
                    "bool": {
                         "must":
                         [
                            '.implode(",\n", $query).'
                         ],
                        "must_not":
                        [
                            '.implode(",\n", $queryNotIn).'
                        ]
                    }
                }'.$sort.'
                 ,"aggs": {
                    "all_interests": {
                      "terms": {
                        "field": "'.$this->getEggField().'"
                      },
                      "aggs": {
                        "daysbuckets": {
                          "date_histogram": { '.$date_histogram.' }
                        }
                      }
                    }
                  }
            }';

        $this->ElasticsearchQuery=(new ElasticsearchQuery());
        return $this->ElasticsearchQuery
            ->setElasticsearchConfig(new <?=(new \ReflectionClass($this->getElasticsearchMakeTool()->getElasticsearchConfig()))->getShortName()?>)
            ->setEgg(true)
            ->setBodyString($bodyString)
            ->setDebug($this->isDebug())
            ->__invoke();

    }



<?php
include __DIR__."/UnitCommom.tpl.php";
?>
