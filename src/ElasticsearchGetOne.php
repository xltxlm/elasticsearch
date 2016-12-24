<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 19:15.
 */

namespace xltxlm\elasticsearch;

use Elasticsearch\ClientBuilder;

/**
 * 获取一行数据
 * Class ElasticsearchGetOne.
 */
class ElasticsearchGetOne extends Elasticsearch
{
    /** @var string 返回结果的模型类 */
    protected $bodyClass;

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
        try {
            $index = $this->getElasticsearchConfig()->__invoke() +
                [
                    'id' => $this->getId(),
                ];
            $client = ClientBuilder::create()->build();
            $response = $client->get($index);

            return (new \ReflectionClass($this->getBodyClass()))
                ->newInstance($response['_source']);
        } catch (\Exception $e) {
            return (new \ReflectionClass($this->getBodyClass()))
                ->newInstance();
        }
    }
}
