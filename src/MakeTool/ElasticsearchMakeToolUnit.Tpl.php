<?php /** @var \xltxlm\elasticsearch\MakeTool\ElasticsearchMakeToolUnit $this */?>
<<?= '?' ?>php

namespace <?=$this->getNameSpace()?>\<?=(new \ReflectionClass($this->getElasticsearchMakeTool()->getElasticsearchConfig()))->getShortName()?>;

use xltxlm\elasticsearch\ElasticsearchQuery;
use \xltxlm\elasticsearch\Unit\ElasticsearchAction;
use xltxlm\page\PageObject;
use <?=(new \ReflectionClass($this->getElasticsearchMakeTool()->getElasticsearchConfig()))->getName()?>;
use <?=$this->getClassName()?>;

final class <?=$this->getClassShortName()?>ElasticsearchQuery
{

    /** @var  PageObject */
    protected $pageObject;



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

        $this->ElasticsearchQuery=(new ElasticsearchQuery());
        return $this->ElasticsearchQuery
            ->setElasticsearchConfig(new <?=(new \ReflectionClass($this->getElasticsearchMakeTool()->getElasticsearchConfig()))->getShortName()?>)
            ->setClassName(<?=(new \ReflectionClass($this->getClassName()))->getShortName()?>::class)
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
include __DIR__."/UnitCommom.tpl.php";
?>
