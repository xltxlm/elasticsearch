<?php /** @var \xltxlm\elasticsearch\MakeTool\ElasticsearchMakeToolUnit $this */?>
<<?= '?' ?>php

namespace <?=$this->getNameSpace()?>;

use xltxlm\elasticsearch\ElasticsearchQuery;
use \xltxlm\elasticsearch\Unit\ElasticsearchAction;
use xltxlm\page\PageObject;
use xltxlm\elasticsearch\Unit\ElasticsearchConfig;

final class <?=$this->getClassShortName()?>ElasticsearchQuery
{

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
            ->setDebug($this->isDebug())
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
include __DIR__."/UnitCommom.tpl.php";
?>
