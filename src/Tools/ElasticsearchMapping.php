<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/2/25
 * Time: 17:26.
 */

namespace xltxlm\elasticsearch\Tools;

use xltxlm\elasticsearch\Unit\ElasticsearchConfig;
use xltxlm\guzzlehttp\Get;
use xltxlm\helper\Hclass\ChangeTo1Array;

/**
 * 指定索引下面的mappings转换成数组
 * Class ElasticsearchMapping.
 */
class ElasticsearchMapping
{
    /** @var ElasticsearchConfig */
    protected $elasticsearchConfig;
    /** @var bool 默认返回的是数组, 可以转换成1维数组,用_链接 */
    protected $to1Array = false;

    /**
     * @return ElasticsearchConfig
     */
    public function getElasticsearchConfig(): ElasticsearchConfig
    {
        return $this->elasticsearchConfig;
    }

    /**
     * @param ElasticsearchConfig $elasticsearchConfig
     * @return ElasticsearchMapping
     */
    public function setElasticsearchConfig(ElasticsearchConfig $elasticsearchConfig): ElasticsearchMapping
    {
        $this->elasticsearchConfig = $elasticsearchConfig;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTo1Array(): bool
    {
        return $this->to1Array;
    }

    /**
     * @param bool $to1Array
     * @return ElasticsearchMapping
     */
    public function setTo1Array(bool $to1Array): ElasticsearchMapping
    {
        $this->to1Array = $to1Array;
        return $this;
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        $url = "http://".$this->elasticsearchConfig->getHost().":".$this->elasticsearchConfig->getPort()."/".$this->elasticsearchConfig->getIndex()."/_mappings";
        $tokenizer = (new Get($url))
            ->__invoke();
        $tokenizer = json_decode($tokenizer, true);
        $this->mapping($tokenizer);
        $tokenizer = current(current(current($tokenizer)));
        //把多为数组转换成一维数组
        if ($this->isTo1Array() && is_array($tokenizer)) {
            return (new ChangeTo1Array)
                ->setArray($tokenizer)
                ->__invoke();
        }
        return $tokenizer;
    }


    /**
     * 把 properties 属性的内容往上级拉
     * @param $array
     */
    private
    function mapping(&$array)
    {
        foreach ($array as $key => &$item) {
            if ($key[0] == '@' || strpos($key, '_') === 0 || $key == 'dynamic_templates') {
                unset($array[$key]);
                continue;
            }
            if ($key == 'properties') {
                unset($array[$key]);
                $array = array_merge($array, $item);
                $this->mapping($array);
                continue;
            }
            if (is_array($item)) {
                if ($item['type']) {
                    $item = $item['type'];
                } else
                    $this->mapping($item);
            }
        }
    }
}
