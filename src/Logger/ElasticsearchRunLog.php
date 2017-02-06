<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/2/6
 * Time: 10:57.
 */

namespace xltxlm\elasticsearch\Logger;

use xltxlm\elasticsearch\Elasticsearch;
use xltxlm\logger\Log\DefineLog;

class ElasticsearchRunLog extends DefineLog
{
    /** @var array 查询语句 */
    protected $queryString = [];

    /**
     * 当前记录的类算在运行类身上,不是orm
     * PdoRunLog constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setReource(Elasticsearch::class);
        $getNamespaceName = (new \ReflectionClass(Elasticsearch::class))->getNamespaceName();

        $debug_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        foreach ($debug_backtrace as $item) {
            if ($item['class'] && strpos($item['class'], $getNamespaceName) === false) {
                $this->setLogClassName($item['class']);
                break;
            }
        }
    }

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
