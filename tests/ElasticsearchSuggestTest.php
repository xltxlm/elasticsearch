<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/2/16
 * Time: 14:01
 */

namespace xltxlm\elasticsearch\tests;


use PHPUnit\Framework\TestCase;
use xltxlm\elasticsearch\ElasticsearchSuggest;
use xltxlm\elk\Config\ElasticsearchWebsitesuggestConfig;

class ElasticsearchSuggestTest extends TestCase
{

    public function test()
    {
        $ElasticsearchSuggest = (new ElasticsearchSuggest())
            ->setElasticsearchConfig(new ElasticsearchWebsitesuggestConfig())
            ->setField('tag')
            ->setPrefix('elk')
            ->__invoke();
        echo "<pre>-->";print_r($ElasticsearchSuggest);echo "<--@in ".__FILE__." on line ".__LINE__."\n";
    }
}