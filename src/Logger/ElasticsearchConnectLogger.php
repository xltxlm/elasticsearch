<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/1/13
 * Time: 9:48.
 */

namespace xltxlm\elasticsearch\Logger;

use xltxlm\elasticsearch\Elasticsearch;
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
     * 当前记录的类算在运行类身上,不是orm
     * PdoRunLog constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setReource(Elasticsearch::class);
    }

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
