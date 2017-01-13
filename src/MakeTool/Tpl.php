<?php /** @var \xltxlm\elasticsearch\MakeTool\ElasticsearchMakeToolUnit $this */?>
<<?= '?' ?>php

namespace <?=$this->getNameSpace()?>\<?=(new \ReflectionClass($this->getElasticsearchMakeTool()->getElasticsearchConfig()))->getShortName()?>;

use xltxlm\elasticsearch\ElasticsearchQuery;
use \xltxlm\elasticsearch\Unit\ElasticsearchAction;
use xltxlm\page\PageObject;

/**
* Created by PhpStorm.
* User: xialintai
* Date: 2017/1/13
* Time: 11:11
*/

final class <?=$this->getClassShortName()?>ElasticsearchQuery
{
    /** @var array 查询的内容  */
    protected $__binds = [];
    /** @var array 区间范围  */
    protected $__ranges = [];
    /** @var array 区间范围  */
    protected $__orderby = [];

    /** @var  PageObject */
    protected $pageObject;

    /**
     * @return \<?=$this->getClassName()?>[]
     */
    public function __invoke()
    {
        $query = [];
        if ($this->__ranges) {
            foreach ($this->__ranges as $field => $bind) {
                $query[] = sprintf('{ "range":{ "%s":{ "%s":"%s" } } }', $field, $bind['action'], $bind['string']);
            }
        }
        $sort = '';
        if ($this->__orderby) {
            $sort = ' ,"sort": [ '.join(",", $this->__orderby).' ] ';
        }

        if (empty($this->__binds)) {
            $query[] = sprintf('{"match_all": {} }');
        } else {
            foreach ($this->__binds as $field => $bind) {
                $query[] = sprintf('{ "%s":{ "%s":"%s" } }', $bind['action'], $field, $bind['string']);
            }
        }
        $bodyString = '{
                "query": {
                    "bool": {
                         "must":
                         [
                            '.implode(",\n", $query).'
                         ]
                    }
                }'.$sort.'
            }';


        return (new ElasticsearchQuery())
            ->setElasticsearchConfig(new \xltxlm\elasticsearch\tests\Resource\VideoBaseConfig())
            ->setClassName(\xltxlm\elasticsearch\tests\Resource\VideoDemoBody::class)
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

<?php
$Properties = $this->getProperties();
/** @var \ReflectionProperty $property */
foreach ($Properties as $property) {
    if($property->getName()[0]=='_' && $property->getName()[1]=='_')
    {
        continue;
    }
?>
    /**
    * @param string $<?=$property->getName()?>

    * @param string $action
    * @return static
    */
    public function where<?=ucfirst($property->getName())?>($<?=$property->getName()?>,$action=ElasticsearchAction::EQUAL)
    {
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
            $this->__ranges['<?=$property->getName()?>'] =
                [
                    'action' => $action,
                    'string' => $<?=$property->getName()?>
                ];
        }
        return $this;
    }
    /**
    * @param string $<?=$property->getName()?>

    * @param string $action
     * 如果值存在,就进行对比
     * @return static
    */
    public function where<?=ucfirst($property->getName())?>Maybe($<?=$property->getName()?>,$action=ElasticsearchAction::EQUAL)
    {
        if(!empty($<?=$property->getName()?>))
        {
            if( $action == ElasticsearchAction::EQUAL)
            {
                $this->__binds['<?=$property->getName()?>'] = $<?=$property->getName()?>;
            }else
            {
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
<?php }?>
}