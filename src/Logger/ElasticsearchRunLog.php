<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/2/6
 * Time: 10:57.
 */

namespace xltxlm\elasticsearch\Logger;

class ElasticsearchRunLog extends ElasticsearchConnectLogger
{
    /** @var array 查询语句 */
    protected $ElasticsearchQueryString = [];

    /**
     * @return array
     */
    public function getElasticsearchQueryString(): array
    {
        return $this->ElasticsearchQueryString;
    }

    /**
     * @param array $ElasticsearchQueryString
     *
     * @return ElasticsearchRunLog
     */
    public function setElasticsearchQueryString(array $ElasticsearchQueryString): ElasticsearchRunLog
    {
        $this->ElasticsearchQueryString = $ElasticsearchQueryString;

        return $this;
    }
}
