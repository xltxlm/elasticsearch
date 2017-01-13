<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/1/13
 * Time: 9:48.
 */

namespace xltxlm\elasticsearch\Logger;

use xltxlm\elasticsearch\Unit\ElasticsearchConfig;
use xltxlm\logger\Log\DefineLog;

/**
 * 链接记录
 * Class ElasticsearchConnectLogger.
 */
class ElasticsearchConnectLogger extends DefineLog
{
    /** @var ElasticsearchConfig */
    protected $ElasticsearchConfig;

    /**
     * @return ElasticsearchConfig
     */
    public function getElasticsearchConfig(): ElasticsearchConfig
    {
        return $this->ElasticsearchConfig;
    }

    /**
     * @param ElasticsearchConfig $ElasticsearchConfig
     *
     * @return ElasticsearchConnectLogger
     */
    public function setElasticsearchConfig(ElasticsearchConfig $ElasticsearchConfig): ElasticsearchConnectLogger
    {
        $this->ElasticsearchConfig = $ElasticsearchConfig;

        return $this;
    }
}
