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
    protected $queryString = [];

    /**
     * @return array
     */
    public function getQueryString(): array
    {
        return $this->queryString;
    }

    /**
     * @param array $queryString
     *
     * @return ElasticsearchRunLog
     */
    public function setQueryString(array $queryString): ElasticsearchRunLog
    {
        $this->queryString = $queryString;

        return $this;
    }
}
