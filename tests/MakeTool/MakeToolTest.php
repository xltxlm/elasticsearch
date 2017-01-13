<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/1/13
 * Time: 11:40
 */

namespace xltxlm\elasticsearch\tests\MakeTool;


use PHPUnit\Framework\TestCase;
use xltxlm\elasticsearch\MakeTool\ElasticsearchMakeTool;
use xltxlm\elasticsearch\tests\Resource\VideoBaseConfig;
use xltxlm\elasticsearch\tests\Resource\VideoDemoBody;

class MakeToolTest extends TestCase
{

    public function test()
    {
        $ElasticsearchMakeTool = (new ElasticsearchMakeTool);
        $ElasticsearchMakeTool
            ->setElasticsearchConfig(new VideoBaseConfig)
            ->setClassNames(VideoDemoBody::class)
            ->__invoke();
    }
}