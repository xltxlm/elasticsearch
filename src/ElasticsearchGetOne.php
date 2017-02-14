<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 19:15.
 */

namespace xltxlm\elasticsearch;

/**
 * 获取一行数据
 * Class ElasticsearchGetOne.
 */
class ElasticsearchGetOne extends Elasticsearch
{
    /** @var string 返回结果的模型类 */
    protected $bodyClass = "";

    /**
     * @return string
     */
    public function getBodyClass(): string
    {
        return $this->bodyClass;
    }

    /**
     * @param string $bodyClass
     *
     * @return ElasticsearchGetOne
     */
    public function setBodyClass(string $bodyClass): ElasticsearchGetOne
    {
        $this->bodyClass = $bodyClass;

        return $this;
    }

    /**
     * 返回模型对象
     *
     * @return mixed
     */
    public function __invoke()
    {

        $index = $this->getElasticsearchConfig()->__invoke() +
            [
                'body' => '{"query":{ "match_phrase": { "_id":"'.$this->getId().'" }  } }'
            ];
        $response = $this->getClient()->search($index);
        if ($this->getBodyClass()) {
            try {
                return (new \ReflectionClass($this->getBodyClass()))
                    ->newInstance($response['_source']);
            } catch (\Exception $e) {
                return (new \ReflectionClass($this->getBodyClass()))
                    ->newInstance();
            }
        }
        return $response;
    }
}
