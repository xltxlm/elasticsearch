<?php

namespace xltxlm\elasticsearch\tests\Resource\VideoBaseConfig;

use xltxlm\elasticsearch\ElasticsearchQuery;
use \xltxlm\elasticsearch\Unit\ElasticsearchAction;
use xltxlm\page\PageObject;

/**
* Created by PhpStorm.
* User: xialintai
* Date: 2017/1/13
* Time: 11:11
*/

final class VideoDemoBodyElasticsearchQuery
{
    /** @var array 查询的内容  */
    protected $__binds = [];
    /** @var array 区间范围  */
    protected $__ranges = [];
    /** @var array 区间范围  */
    protected $__orderby = [];

    /** @var string 模糊检索的字符串  */
    protected $__string = "";

    /** @var  PageObject */
    protected $pageObject;

    /**
     * @return \xltxlm\elasticsearch\tests\Resource\VideoDemoBody[]
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

        if (!empty($this->__string)) {
            $query[] = sprintf('{"query_string": { "query": "%s"} }', $this->__string);
        }
        foreach ($this->__binds as $field => $bind) {
            $query[] = sprintf('{ "%s":{ "%s":"%s" } }', $bind['action'], $field, $bind['string']);
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


    /**
    * @param string $id
    * @param string $action
    * @return static
    */
    public function whereId($id,$action=ElasticsearchAction::EQUAL)
    {
        if( in_array( $action , [ ElasticsearchAction::EQUAL ,ElasticsearchAction::LIKE ]) )
        {
            $this->__binds['id'] =
                [
                    'action' => $action,
                    'string' => $id                ];
        }

        if( in_array( $action , [ ElasticsearchAction::MORE ,ElasticsearchAction::LESS, ElasticsearchAction::MOREANDEQUAL, ElasticsearchAction::LESSANDEQUAL ]) )
        {
            $this->__ranges['id'] =
                [
                    'action' => $action,
                    'string' => $id                ];
        }
        return $this;
    }
    /**
    * @param string $id
    * @param string $action
     * 如果值存在,就进行对比
     * @return static
    */
    public function whereIdMaybe($id,$action=ElasticsearchAction::EQUAL)
    {
        if(!empty($id))
        {
            if( $action == ElasticsearchAction::EQUAL)
            {
                $this->__binds['id'] = $id;
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
    public function orderbyIdAsc()
    {
        $this->__orderby['id'] = '{"id" : "asc"}';
        return $this;
    }


    /**
     * 排序:倒序
     * @return static
    */
    public function orderbyIdDesc()
    {
        $this->__orderby['id'] = '{"id" : "desc"}';
        return $this;
    }
    /**
    * @param string $title
    * @param string $action
    * @return static
    */
    public function whereTitle($title,$action=ElasticsearchAction::EQUAL)
    {
        if( in_array( $action , [ ElasticsearchAction::EQUAL ,ElasticsearchAction::LIKE ]) )
        {
            $this->__binds['title'] =
                [
                    'action' => $action,
                    'string' => $title                ];
        }

        if( in_array( $action , [ ElasticsearchAction::MORE ,ElasticsearchAction::LESS, ElasticsearchAction::MOREANDEQUAL, ElasticsearchAction::LESSANDEQUAL ]) )
        {
            $this->__ranges['title'] =
                [
                    'action' => $action,
                    'string' => $title                ];
        }
        return $this;
    }
    /**
    * @param string $title
    * @param string $action
     * 如果值存在,就进行对比
     * @return static
    */
    public function whereTitleMaybe($title,$action=ElasticsearchAction::EQUAL)
    {
        if(!empty($title))
        {
            if( $action == ElasticsearchAction::EQUAL)
            {
                $this->__binds['title'] = $title;
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
    public function orderbyTitleAsc()
    {
        $this->__orderby['title'] = '{"title" : "asc"}';
        return $this;
    }


    /**
     * 排序:倒序
     * @return static
    */
    public function orderbyTitleDesc()
    {
        $this->__orderby['title'] = '{"title" : "desc"}';
        return $this;
    }
    /**
    * @param string $money
    * @param string $action
    * @return static
    */
    public function whereMoney($money,$action=ElasticsearchAction::EQUAL)
    {
        if( in_array( $action , [ ElasticsearchAction::EQUAL ,ElasticsearchAction::LIKE ]) )
        {
            $this->__binds['money'] =
                [
                    'action' => $action,
                    'string' => $money                ];
        }

        if( in_array( $action , [ ElasticsearchAction::MORE ,ElasticsearchAction::LESS, ElasticsearchAction::MOREANDEQUAL, ElasticsearchAction::LESSANDEQUAL ]) )
        {
            $this->__ranges['money'] =
                [
                    'action' => $action,
                    'string' => $money                ];
        }
        return $this;
    }
    /**
    * @param string $money
    * @param string $action
     * 如果值存在,就进行对比
     * @return static
    */
    public function whereMoneyMaybe($money,$action=ElasticsearchAction::EQUAL)
    {
        if(!empty($money))
        {
            if( $action == ElasticsearchAction::EQUAL)
            {
                $this->__binds['money'] = $money;
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
    public function orderbyMoneyAsc()
    {
        $this->__orderby['money'] = '{"money" : "asc"}';
        return $this;
    }


    /**
     * 排序:倒序
     * @return static
    */
    public function orderbyMoneyDesc()
    {
        $this->__orderby['money'] = '{"money" : "desc"}';
        return $this;
    }
    /**
    * @param string $tag
    * @param string $action
    * @return static
    */
    public function whereTag($tag,$action=ElasticsearchAction::EQUAL)
    {
        if( in_array( $action , [ ElasticsearchAction::EQUAL ,ElasticsearchAction::LIKE ]) )
        {
            $this->__binds['tag'] =
                [
                    'action' => $action,
                    'string' => $tag                ];
        }

        if( in_array( $action , [ ElasticsearchAction::MORE ,ElasticsearchAction::LESS, ElasticsearchAction::MOREANDEQUAL, ElasticsearchAction::LESSANDEQUAL ]) )
        {
            $this->__ranges['tag'] =
                [
                    'action' => $action,
                    'string' => $tag                ];
        }
        return $this;
    }
    /**
    * @param string $tag
    * @param string $action
     * 如果值存在,就进行对比
     * @return static
    */
    public function whereTagMaybe($tag,$action=ElasticsearchAction::EQUAL)
    {
        if(!empty($tag))
        {
            if( $action == ElasticsearchAction::EQUAL)
            {
                $this->__binds['tag'] = $tag;
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
    public function orderbyTagAsc()
    {
        $this->__orderby['tag'] = '{"tag" : "asc"}';
        return $this;
    }


    /**
     * 排序:倒序
     * @return static
    */
    public function orderbyTagDesc()
    {
        $this->__orderby['tag'] = '{"tag" : "desc"}';
        return $this;
    }
}