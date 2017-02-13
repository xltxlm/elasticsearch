<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 16:48.
 */

namespace xltxlm\elasticsearch;

use xltxlm\elasticsearch\Unit\ElasticsearchModel;
use xltxlm\helper\Hclass\ConvertObject;

/**
 * 写入数据
 * Class Insert.
 */
final class ElasticsearchInsert extends Elasticsearch
{
    /** @var ElasticsearchModel 写入数据 */
    protected $body;

    /**
     * @return ElasticsearchModel
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     *
     * @return ElasticsearchInsert
     */
    public function setBody($body): ElasticsearchInsert
    {
        $this->body = $body;

        return $this;
    }

    /**
     * 执行写入操作.
     */
    public function __invoke()
    {
        $index = $this->getElasticsearchConfig()->__invoke() +
            [
                'id' => $this->getId(),
                'body' => (new ConvertObject($this->getBody()))->toArray() +
                    [
                        'elasticsearch_update_time' => date('Y-m-d H:i:s'),
                    ],
            ];
        $this->getClient()->index($index);

        return true;
    }
}
