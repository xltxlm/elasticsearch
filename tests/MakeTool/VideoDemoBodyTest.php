<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/1/13
 * Time: 12:29.
 */

namespace xltxlm\elasticsearch\tests\MakeTool;

use PHPUnit\Framework\TestCase;
use \xltxlm\elasticsearch\tests\Resource\VideoBaseConfig\VideoDemoBodyElasticsearchQuery;
use xltxlm\elasticsearch\Unit\ElasticsearchAction;
use xltxlm\page\PageObject;

class VideoDemoBodyTest extends TestCase
{
    /**
     * 测试等于. 单项
     */
    public function testEqual1()
    {
        $pageObject = (new PageObject())
            ->setPrepage(2);
        $tag = "手机";
        $VideoDemoBodyElasticsearchQuery = (new VideoDemoBodyElasticsearchQuery())
            ->setPageObject($pageObject)
            ->whereTag($tag)
            ->__invoke();
        /** @var  \xltxlm\elasticsearch\tests\Resource\VideoDemoBody $item */
        foreach ($VideoDemoBodyElasticsearchQuery as $item) {
            $this->assertEquals($item->getTag(), $tag);
        }
    }

    /**
     * 测试等于. 多项
     */
    public function testEqualMore()
    {
        $pageObject = (new PageObject())
            ->setPrepage(2);
        $tag = "手机";
        $VideoDemoBodyElasticsearchQuery = (new VideoDemoBodyElasticsearchQuery())
            ->setPageObject($pageObject)
            ->whereTag($tag)
            ->whereId('6208554939514357761')
            ->__invoke();
        $this->assertEquals(1, count($VideoDemoBodyElasticsearchQuery));
        /** @var  \xltxlm\elasticsearch\tests\Resource\VideoDemoBody $item */
        foreach ($VideoDemoBodyElasticsearchQuery as $item) {
            $this->assertEquals($item->getTag(), $tag);
        }
    }

    /**
     * 测试等于. 排序
     */
    public function testEqualMoreOrder()
    {
        $pageObject = (new PageObject())
            ->setPrepage(20);
        $tag = "手机";
        $VideoDemoBodyElasticsearchQuery = (new VideoDemoBodyElasticsearchQuery())
            ->setPageObject($pageObject)
            ->whereTag($tag)
            ->orderbyIdDesc()
            ->orderbyMoneyDesc()
            ->__invoke();
        $old = 0;
        /** @var  \xltxlm\elasticsearch\tests\Resource\VideoDemoBody $item */
        foreach ($VideoDemoBodyElasticsearchQuery as $item) {
            if ($old) {
                $this->assertGreaterThan($item->getId(), $old);
            }
            $old = $item->getId();
        }
    }

    /**
     * 测试模糊查询全部字段
     */
    public function testALLField()
    {
        $pageObject = (new PageObject())
            ->setPrepage(20);

        $tag = "手机";
        //不带关键词,命中全部数据
        $VideoDemoBodyElasticsearchQuery = (new VideoDemoBodyElasticsearchQuery())
            ->setPageObject($pageObject)
            ->where("")
            ->__invoke();
        $tag = "手机";
        $VideoDemoBodyElasticsearchQuery = (new VideoDemoBodyElasticsearchQuery())
            ->setPageObject($pageObject)
            ->where($tag)
            ->orderbyIdDesc()
            ->orderbyMoneyDesc()
            ->__invoke();
        $old = 0;
        /** @var  \xltxlm\elasticsearch\tests\Resource\VideoDemoBody $item */
        foreach ($VideoDemoBodyElasticsearchQuery as $item) {
            if ($old) {
                $this->assertGreaterThan($item->getId(), $old);
            }
            $old = $item->getId();
        }
    }

    /**
     * 测试等于. 排序,区间范围
     */
    public function testEqualMoreOrderRange()
    {
        $pageObject = (new PageObject())
            ->setPrepage(20);
        $tag = "手机";
        $VideoDemoBodyElasticsearchQuery = (new VideoDemoBodyElasticsearchQuery())
            ->setPageObject($pageObject)
            ->whereTag($tag)
            ->whereMoney(200, ElasticsearchAction::MOREANDEQUAL)
            ->whereId(2, ElasticsearchAction::MOREANDEQUAL)
            ->orderbyIdDesc()
            ->__invoke();
        $old = 0;
        /** @var  \xltxlm\elasticsearch\tests\Resource\VideoDemoBody $item */
        foreach ($VideoDemoBodyElasticsearchQuery as $item) {
            if ($old) {
                $this->assertGreaterThan($item->getId(), $old);
            }
            $old = $item->getId();
        }
    }

    /**
     * 测试等于. 多项,模糊搜索
     */
    public function testEqualLikeMore()
    {
        $pageObject = (new PageObject())
            ->setPrepage(2);
        $tag = "手机";
        $title = "联想";
        $VideoDemoBodyElasticsearchQuery = (new VideoDemoBodyElasticsearchQuery())
            ->setPageObject($pageObject)
            ->whereTag($tag)
            ->whereTitle($title, ElasticsearchAction::LIKE)
            ->__invoke();
        /** @var  \xltxlm\elasticsearch\tests\Resource\VideoDemoBody $item */
        foreach ($VideoDemoBodyElasticsearchQuery as $item) {
            $this->assertEquals($item->getTag(), $tag);
            $this->assertTrue(strpos($item->getTitle(), $title) !== false);
        }
    }
}
