<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 18:52
 */

namespace xltxlm\elasticsearch\tests;


use xltxlm\elasticsearch\ElasticsearchDelete;
use xltxlm\elasticsearch\ElasticsearchGetOne;
use xltxlm\elasticsearch\ElasticsearchInsert;
use xltxlm\elasticsearch\tests\Resource\VideoBaseConfig;
use xltxlm\elasticsearch\tests\Resource\VideoDemoBody;
use xltxlm\helper\Hclass\ObjectToArray;

class InsertAndDeleteTest extends \PHPUnit_Framework_TestCase
{

    private $id = 1;

    public function testInsert()
    {
        //因为  VideoDemoBody 的自增id名称不一定叫做id, 所以还要再设置一次id
        (new ElasticsearchInsert())
            ->setElasticsearchConfig(new VideoBaseConfig)
            ->setId($this->id)
            ->setBody(
                (new VideoDemoBody)
                    ->setId($this->id)
                    ->setTitle("视频标题".date('Y-m-d H:i:s'))
            )
            ->__invoke();

        (new ElasticsearchInsert())
            ->setElasticsearchConfig(new VideoBaseConfig)
            ->setId($this->id + 1)
            ->setBody(
                (new VideoDemoBody)
                    ->setId($this->id + 1)
                    ->setTitle("视频标题".date('Y-m-d H:i:s'))
            )
            ->__invoke();
    }

    /**
     * 测试删除
     */
    public function testDelete()
    {
        (new ElasticsearchDelete())
            ->setElasticsearchConfig(new VideoBaseConfig())
            ->setId($this->id)
            ->__invoke();

        /** @var VideoDemoBody $data */
        $data = (new ElasticsearchGetOne())
            ->setElasticsearchConfig(new VideoBaseConfig())
            ->setId($this->id)
            ->setBodyClass(VideoDemoBody::class)
            ->__invoke();

        $this->assertEmpty($data->getId());
    }

    public function testGetOne()
    {
        $data = (new ElasticsearchGetOne())
            ->setElasticsearchConfig(new VideoBaseConfig())
            ->setId($this->id)
            ->setBodyClass(VideoDemoBody::class)
            ->__invoke();
        echo "<pre>-->";print_r($data);echo "<--@in ".__FILE__." on line ".__LINE__."\n";
        $this->assertInstanceOf(VideoDemoBody::class, $data);
    }

}
