<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/1/4
 * Time: 17:49.
 */

namespace xltxlm\elasticsearch\tests\Config;

use xltxlm\elasticsearch\ElasticsearchQuery;
use xltxlm\elasticsearch\tests\Resource\PasswdConfig;
use xltxlm\page\PageObject;

include __DIR__ . '/../../vendor/autoload.php';

(new PasswdConfig())
    ->test();

$pageObject = new PageObject();

$a = (new ElasticsearchQuery())
    ->setElasticsearchConfig(new PasswdConfig())
    ->setClassName(\stdClass::class)
    ->setPageObject($pageObject)
    ->setBodyString('
    {
"query": {
        "match_all": {}
    }    
    }')
    ->__invoke();

echo "<pre>-->";
print_r($a);
echo "<--@in " . __FILE__ . " on line " . __LINE__ . "\n";