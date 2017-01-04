<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/1/4
 * Time: 17:49
 */

namespace xltxlm\elasticsearch\tests\Config;

use xltxlm\elasticsearch\tests\Resource\VideoBaseConfig;

include __DIR__.'/../../vendor/autoload.php';

(new VideoBaseConfig)
    ->test();