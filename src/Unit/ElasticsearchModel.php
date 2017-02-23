<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 23:05.
 */

namespace xltxlm\elasticsearch\Unit;

use xltxlm\helper\Hclass\LoadFromArray;
use xltxlm\helper\Hclass\ObjectToArray;

/**
 * 检索类的基础模型
 * Class ElasticsearchModel.
 */
abstract class ElasticsearchModel
{
    use LoadFromArray;
    use ObjectToArray;
}
