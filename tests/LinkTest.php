<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 17:22
 */

namespace xltxlm\elasticsearch\tests;


use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{

    /**
     * 测试连接上服务
     */
    public function testConnect()
    {
        $client = ClientBuilder::create()->build();
        $this->assertInstanceOf(Client::class, $client);
    }


    public function testCreatData()
    {
        $client = ClientBuilder::create()->build();
        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'id' => '2',
            'body' =>
                [
                    'testField1' => 'abc'.__LINE__,
                    'testField2' => 'abc'.__LINE__,
                ],
        ];

        $response = $client->index($params);
        print_r($response);
    }

    /**
     * 同时测试,把docker重启的情况,再次查询,数据还是在的
     */
    public function testGetData()
    {
        $client = ClientBuilder::create()->build();
        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'id' => '2'
        ];

        $response = $client->get($params);
        print_r($response);
    }

}
