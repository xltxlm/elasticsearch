<?php /** @var \xltxlm\elasticsearch\MakeTool\ElasticsearchMakeToolUnit $this */?>
<<?= '?' ?>php

namespace <?=$this->getNameSpace()?>\<?=(new \ReflectionClass($this->getElasticsearchMakeTool()->getElasticsearchConfig()))->getShortName()?>;

use \xltxlm\elasticsearch\Elasticsearch;
use xltxlm\elasticsearch\ElasticsearchQuery;
use \xltxlm\elasticsearch\Unit\ElasticsearchAction;
use <?=(new \ReflectionClass($this->getElasticsearchMakeTool()->getElasticsearchConfig()))->getName()?>;

final class <?=$this->getClassShortName()?>ElasticsearchEgg
{
    /** @var array 查询的内容  */
    protected $__binds = [];
    /** @var array 区间范围  */
    protected $__ranges = [];
    /** @var array 区间范围  */
    protected $__orderby = [];
    /** @var array 被排除的关键词数组  */
    protected $__notin = [];

    /** @var string 模糊检索的字符串  */
    protected $__string = "";

    /** @var string 要分组的字段 */
    protected $eggField = "";
    /** @var string 分组的日期字段 */
    protected $eggDayField = "";
    /** @var string x轴时间纬度,默认是按天 */
    protected $eggDayType = Elasticsearch::DAY;

    /** @var ElasticsearchQuery */
    protected $ElasticsearchQuery;

    /**
     * @return ElasticsearchQuery
     */
    public function getElasticsearchQuery(): ElasticsearchQuery
    {
        return $this->ElasticsearchQuery;
    }

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

        if($this->getEggDayType() == Elasticsearch::DAY)
        {
            $date_histogram='
                "field": "'.$this->getEggDayField().'",
                "format": "yyyy-MM-dd",
                "interval": "day",
                "time_zone": "+08:00"
            ';
        }else
        {
            $date_histogram='
            "field": "'.$this->getEggDayField().'",
            "format": "yyyy-MM-dd HH",
            "interval": "hour",
            "time_zone": "+08:00"
            ';
        }

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
            ->__invoke();

    }

    /**
     * 模糊检索,全部字段都查询
     * @return static
     */
    public function where($keyword)
    {
        if($keyword)
        {
                $this->__string = $keyword;
        }
        return $this;
    }


<?php
$Properties = $this->getProperties();
/** @var \ReflectionProperty $property */
foreach ($Properties as $property) {
    if ($property->getName()[0] == '_' && $property->getName()[1] == '_') {
        continue;
    } ?>
    /**
    * @param string $<?=$property->getName()?>

    * @param string $action
    * @param string $explode
    * @param string|bool $timeZone
    * @return static
    */
    public function where<?=ucfirst($property->getName())?>($<?=$property->getName()?>,$action=ElasticsearchAction::EQUAL, $explode=" - ",$timeZone=false)
    {
        $<?=$property->getName()?>=is_string($<?=$property->getName()?>)?trim($<?=$property->getName()?>):$<?=$property->getName()?>;
        if(empty($<?=$property->getName()?>))
        {
            return $this;
        }
        if( in_array( $action , [ ElasticsearchAction::EQUAL ,ElasticsearchAction::LIKE ]) )
        {
            $this->__binds['<?=$property->getName()?>'] =
                [
                    'action' => $action,
                    'string' => $<?=$property->getName()?>
                ];
        }

        if( in_array( $action , [ ElasticsearchAction::MORE ,ElasticsearchAction::LESS, ElasticsearchAction::MOREANDEQUAL, ElasticsearchAction::LESSANDEQUAL ]) )
        {
            $this->__ranges['<?=$property->getName()?>'] = sprintf('{ "range":{ "<?=$property->getName()?>":{ "%s":"%s" } } }',  $action, $<?=$property->getName()?>);
        }
        if( in_array( $action , [ ElasticsearchAction::IN_EQUAL ,ElasticsearchAction::IN_EQUAL ]) )
        {
            list($ltval,$gtval)=explode($explode,$<?=$property->getName()?>);
            list($lt,$gt)=explode("|",$action);
            if($timeZone)
            {
                $this->__ranges['<?=$property->getName()?>'] = sprintf('{ "range":{ "<?=$property->getName()?>":{ "%s":"%s","%s":"%s","time_zone":"%s" } } }',  $lt,$ltval,$gt,$gtval,$timeZone);
            }else
            {
                $this->__ranges['<?=$property->getName()?>'] = sprintf('{ "range":{ "<?=$property->getName()?>":{ "%s":"%s","%s":"%s" } } }',  $lt,$ltval,$gt,$gtval);
            }
        }
        return $this;
    }
    /**
    * @param string $<?=$property->getName()?>

    * @param string $action
    * @return static
    */
    public function where<?=ucfirst($property->getName())?>Notin($<?=$property->getName()?>,$action=ElasticsearchAction::EQUAL)
    {
        $<?=$property->getName()?>=is_string($<?=$property->getName()?>)?trim($<?=$property->getName()?>):$<?=$property->getName()?>;
        if(empty($<?=$property->getName()?>))
        {
            return $this;
        }
        if( in_array( $action , [ ElasticsearchAction::EQUAL ]) )
        {
            foreach( $<?=$property->getName()?> as $item)
            {
                $this->__notin[] = sprintf('{ "%s":{ "<?=$property->getName()?>": "%s" } }',  ElasticsearchAction::EQUAL,$item);
            }
        }
        return $this;
    }

    /**
     * 排序:正序
     * @return static
    */
    public function orderby<?=ucfirst($property->getName())?>Asc()
    {
        $this->__orderby['<?=$property->getName()?>'] = '{"<?=$property->getName()?>" : "asc"}';
        return $this;
    }


    /**
     * 排序:倒序
     * @return static
    */
    public function orderby<?=ucfirst($property->getName())?>Desc()
    {
        $this->__orderby['<?=$property->getName()?>'] = '{"<?=$property->getName()?>" : "desc"}';
        return $this;
    }
<?php 
}?>
}