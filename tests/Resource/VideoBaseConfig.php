<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 18:59.
 */

namespace xltxlm\elasticsearch\tests\Resource;

use xltxlm\elasticsearch\Unit\ElasticsearchConfig;

/**
 * Class DataBaseConfig.
 */
final class VideoBaseConfig extends ElasticsearchConfig
{
    protected $index="core";
    protected $type="video";
}
