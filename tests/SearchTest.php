<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 21:07
 */

namespace xltxlm\elasticsearch\tests;


use PHPUnit\Framework\TestCase;
use xltxlm\elasticsearch\ElasticsearchGetOne;
use xltxlm\elasticsearch\ElasticsearchInsert;
use xltxlm\elasticsearch\ElasticsearchSearch;
use xltxlm\elasticsearch\tests\Resource\VideoBaseConfig;
use xltxlm\elasticsearch\tests\Resource\VideoDemoBody;
use xltxlm\orm\PageObject;

class SearchTest extends TestCase
{
    protected function setUp()
    {
        $i = 10;
        $VideoDemoBody1 = (new VideoDemoBody())
            ->setId($i++)
            ->setTitle("中文");
        $VideoDemoBody2 = (new VideoDemoBody())
            ->setId($i++)
            ->setTitle("长江");
        $VideoDemoBody3 = (new VideoDemoBody())
            ->setId($i++)
            ->setTitle("南京 ".__LINE__);
        $VideoDemoBody4 = (new VideoDemoBody())
            ->setId($i++)
            ->setTitle("南京 ".__LINE__);
        $VideoDemoBody5 = (new VideoDemoBody())
            ->setId($i++)
            ->setTitle("南京 ".__LINE__);
        $VideoDemoBody6 = (new VideoDemoBody())
            ->setId($i++)
            ->setTitle("南京 ".__LINE__);

        $ElasticsearchInsert = (new ElasticsearchInsert())
            ->setElasticsearchConfig(new VideoBaseConfig());

        $ElasticsearchInsert
            ->setId($VideoDemoBody1->getId())
            ->setBody($VideoDemoBody1)
            ->__invoke();
        $ElasticsearchInsert
            ->setId($VideoDemoBody2->getId())
            ->setBody($VideoDemoBody2)
            ->__invoke();
        $ElasticsearchInsert
            ->setId($VideoDemoBody3->getId())
            ->setBody($VideoDemoBody3)
            ->__invoke();
        $ElasticsearchInsert
            ->setId($VideoDemoBody4->getId())
            ->setBody($VideoDemoBody4)
            ->__invoke();
        $ElasticsearchInsert
            ->setId($VideoDemoBody5->getId())
            ->setBody($VideoDemoBody5)
            ->__invoke();
        $ElasticsearchInsert
            ->setId($VideoDemoBody6->getId())
            ->setBody($VideoDemoBody6)
            ->__invoke();
    }

    /**
     * 只搜索一个字段,返回多个结果,并且可以排序
     */
    public function testSearchOneAndOrderDesc()
    {
        //检索结果应该是4个结果, 按照id进行倒序排列
        $searchVideoDemoBody = (new VideoDemoBody())
            ->setTitle("南京");

        /** @var VideoDemoBody[] $ElasticsearchSearch */
        $ElasticsearchSearch = (new ElasticsearchSearch())
            ->setElasticsearchConfig(new VideoBaseConfig())
            ->setBodyObject($searchVideoDemoBody)
            ->setOrderByDesc($searchVideoDemoBody->varName($searchVideoDemoBody->getId()))
            ->__invoke();
        //1:返回结果不是空的
        $this->assertGreaterThan(1, count($ElasticsearchSearch));
        //2:结果的id 一个比下一个大
        $oldid = null;
        foreach ($ElasticsearchSearch as $item) {
            if ($oldid) {
                $this->assertGreaterThan($item->getId(), $oldid);
            }
            $oldid = $item->getId();
        }
    }

    /**
     * 只搜索一个字段,返回多个结果,并且可以排序
     */
    public function testSearchOneAndOrderAsc()
    {
        //检索结果应该是4个结果, 按照id进行倒序排列
        $searchVideoDemoBody = (new VideoDemoBody())
            ->setTitle("南京");

        /** @var VideoDemoBody[] $ElasticsearchSearch */
        $ElasticsearchSearch = (new ElasticsearchSearch())
            ->setElasticsearchConfig(new VideoBaseConfig())
            ->setBodyObject($searchVideoDemoBody)
            ->setOrderByAsc($searchVideoDemoBody->varName($searchVideoDemoBody->getId()))
            ->__invoke();
        //1:返回结果不是空的
        $this->assertGreaterThan(1, count($ElasticsearchSearch));
        //2:结果的id 一个比下一个大
        $oldid = null;
        foreach ($ElasticsearchSearch as $item) {
            if ($oldid) {
                $this->assertGreaterThan($oldid, $item->getId());
            }
            $oldid = $item->getId();
        }
    }

    /**
     * 只搜索一个字段,返回多个结果,并且可以排序 + 分页查询
     */
    public function testSearchOneAndOrderAscPage()
    {
        //检索结果应该是4个结果, 按照id进行倒序排列
        $searchVideoDemoBody = (new VideoDemoBody())
            ->setTitle("南京");

        $pageObject = (new PageObject())
            ->setPageID(1)
            ->setPrepage(1);

        /** @var VideoDemoBody[] $ElasticsearchSearch1 */
        $ElasticsearchSearch1 = (new ElasticsearchSearch())
            ->setElasticsearchConfig(new VideoBaseConfig())
            ->setBodyObject($searchVideoDemoBody)
            ->setOrderByAsc($searchVideoDemoBody->varName($searchVideoDemoBody->getId()))
            ->setPageObject($pageObject)
            ->__invoke();
        $this->assertEquals(1, count($ElasticsearchSearch1));

        $pageObject = (new PageObject())
            ->setPageID(2)
            ->setPrepage(1);

        /** @var VideoDemoBody[] $ElasticsearchSearch2 */
        $ElasticsearchSearch2 = (new ElasticsearchSearch())
            ->setElasticsearchConfig(new VideoBaseConfig())
            ->setBodyObject($searchVideoDemoBody)
            ->setOrderByAsc($searchVideoDemoBody->varName($searchVideoDemoBody->getId()))
            ->setPageObject($pageObject)
            ->__invoke();
        //1:返回结果不是空的
        $this->assertEquals(1, count($ElasticsearchSearch2));
        $this->assertGreaterThan($ElasticsearchSearch1[0]->getId(), $ElasticsearchSearch2[0]->getId());
    }

    /**
     * 多字段组合and查询
     */
    public function testMoreCondition()
    {
        $searchVideoDemoBody = (new VideoDemoBody())
            ->setTitle("南京")
            ->setId(13);

        $ElasticsearchSearch = (new ElasticsearchSearch())
            ->setElasticsearchConfig(new VideoBaseConfig())
            ->setBodyObject($searchVideoDemoBody)
            ->__invoke();
        $this->assertEquals(count($ElasticsearchSearch), 1);
    }
}