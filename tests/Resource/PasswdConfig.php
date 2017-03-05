<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-03-05
 * Time: 上午 10:56.
 */

namespace xltxlm\elasticsearch\tests\Resource;

use xltxlm\elasticsearch\Unit\ElasticsearchConfig;

/**
 * 通过nginx代理,有密码的配置
 * Class PasswdConfig.
 */
class PasswdConfig extends ElasticsearchConfig
{
    protected $user = 'abc';
    protected $pass = '123';
}
