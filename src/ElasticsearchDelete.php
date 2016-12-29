<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 17:38.
 */

namespace xltxlm\elasticsearch;

use Elasticsearch\ClientBuilder;

/**
 * Class Delete.
 */
final class ElasticsearchDelete extends Elasticsearch
{
    /**
     * 执行删除动作.
     */
    public function __invoke()
    {
        try {
            $index = $this->getElasticsearchConfig()->__invoke() +
                [
                    'id' => $this->getId(),
                ];
            $this->getClient()->delete($index);
        } catch (\Exception $e) {
        }
    }
}
