<?php /** @var \xltxlm\elasticsearch\MakeTool\ElasticsearchMakeToolUnit $this */?>
<<?= '?' ?>php

namespace <?=$this->getNameSpace()?>;

use xltxlm\elasticsearch\ElasticsearchQuery;
use \xltxlm\elasticsearch\Unit\ElasticsearchAction;
use xltxlm\page\PageObject;
use xltxlm\elasticsearch\Unit\ElasticsearchConfig;

final class <?=$this->getClassShortName()?>ElasticsearchQuery
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

    /** @var  PageObject */
    protected $pageObject;

    /** @var  ElasticsearchConfig */
    protected $ElasticsearchConfig;

    /**
     * @return ElasticsearchConfig
     */
    public function getElasticsearchConfig(): ElasticsearchConfig
    {
        return $this->ElasticsearchConfig;
    }

    /**
     * @param ElasticsearchConfig $ElasticsearchConfig
     * @return $this
     */
    public function setElasticsearchConfig(ElasticsearchConfig $ElasticsearchConfig)
    {
        $this->ElasticsearchConfig = $ElasticsearchConfig;
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

        $bodyString = '{
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
            }';


        return (new ElasticsearchQuery())
            ->setElasticsearchConfig($this->getElasticsearchConfig())
            ->setClassName(<?=$this->getClassShortName()?>::class)
            //由于下面有踢出结果的操作,查询结果放大3倍
            ->setPageObject($this->getPageObject())
            ->setBodyString($bodyString)
            ->__invoke();

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
     * @return static
     */
    public function setPageObject(PageObject $pageObject)
    {
        $this->pageObject = $pageObject;
        return $this;
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

    * @param string $Elasticsearchaction
    * @return static
    */
    public function where<?=ucfirst($property->getName())?>($<?=$property->getName()?>,$Elasticsearchaction=ElasticsearchAction::EQUAL, $explode=" - ")
    {
        $<?=$property->getName()?>=is_string($<?=$property->getName()?>)?trim($<?=$property->getName()?>):$<?=$property->getName()?>;
        if(empty($<?=$property->getName()?>))
        {
            return $this;
        }
        if( in_array( $Elasticsearchaction , [ ElasticsearchAction::EQUAL ,ElasticsearchAction::LIKE ]) )
        {
            $this->__binds['<?=$property->getName()?>'] =
                [
                    'action' => $Elasticsearchaction,
                    'string' => $<?=$property->getName()?>
                ];
        }

        if( in_array( $Elasticsearchaction , [ ElasticsearchAction::MORE ,ElasticsearchAction::LESS, ElasticsearchAction::MOREANDEQUAL, ElasticsearchAction::LESSANDEQUAL ]) )
        {
            $this->__ranges['<?=$property->getName()?>'] = sprintf('{ "range":{ "<?=$property->getName()?>":{ "%s":"%s" } } }',  $Elasticsearchaction, $<?=$property->getName()?>);
        }
        if( in_array( $Elasticsearchaction , [ ElasticsearchAction::IN_EQUAL ,ElasticsearchAction::IN_EQUAL ]) )
        {
            list($ltval,$gtval)=explode($explode,$<?=$property->getName()?>);
            list($lt,$gt)=explode("|",$Elasticsearchaction);
            $this->__ranges['<?=$property->getName()?>'] = sprintf('{ "range":{ "<?=$property->getName()?>":{ "%s":"%s","%s":"%s" } } }',  $lt,$ltval,$gt,$gtval);
        }
        return $this;
    }
    /**
    * @param string $<?=$property->getName()?>

    * @param string $Elasticsearchaction
    * @return static
    */
    public function where<?=ucfirst($property->getName())?>Notin($<?=$property->getName()?>,$Elasticsearchaction=ElasticsearchAction::EQUAL)
    {
        $<?=$property->getName()?>=is_string($<?=$property->getName()?>)?trim($<?=$property->getName()?>):$<?=$property->getName()?>;
        if(empty($<?=$property->getName()?>))
        {
            return $this;
        }
        if( in_array( $Elasticsearchaction , [ ElasticsearchAction::EQUAL ]) )
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