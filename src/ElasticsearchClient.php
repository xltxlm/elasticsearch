<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/3/17
 * Time: 16:46.
 */

namespace xltxlm\elasticsearch;

use xltxlm\elasticsearch\Elasticsearch;

/**
 * 获取客户端对象
 * Class ElasticsearchClient.
 */
final class ElasticsearchClient extends Elasticsearch
{
    /**
     * @return \Elasticsearch\Client
     */
    public function __invoke()
    {
        return $this->getClient();
    }
}
