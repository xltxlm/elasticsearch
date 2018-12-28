<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/25
 * Time: 22:33.
 */

namespace xltxlm\elasticsearch;

use xltxlm\logger\Operation\Action\ElasticsearchRunLog;
use xltxlm\page\PageObject;

/**
 * 建议查询，根据提供的字符串，查询后续现实的单词
 * Class ElasticsearchQuery.
 */
class ElasticsearchSuggest extends Elasticsearch
{
    /** @var string 返回的对象类型 */
    protected $className = \stdClass::class;
    /** @var string 前缀 */
    protected $prefix = "";
    /** @var string 推荐词存储的字段 */
    protected $field = "";

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * @return ElasticsearchSuggest
     */
    public function setPrefix(string $prefix): ElasticsearchSuggest
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     * @return ElasticsearchSuggest
     */
    public function setField(string $field): ElasticsearchSuggest
    {
        $this->field = $field;
        return $this;
    }


    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     *
     * @return ElasticsearchSuggest
     */
    public function setClassName(string $className): ElasticsearchSuggest
    {
        $this->className = $className;

        return $this;
    }

    public function __invoke(): array
    {
        $index = $this->getElasticsearchConfig()->__invoke() +
            [
                'body' => '
            {
            "suggest": {
                "tag-suggest": {
                  "prefix": "'.$this->getPrefix().'",
                  "completion": {
                    "field": "'.$this->getField().'",
                    "fuzzy": {
                      "fuzziness": "auto"
                    }
                  }
                }
              }
            }',
            ];

        $response = $this->getClient()->search($index);
        (new ElasticsearchRunLog($this->getElasticsearchConfig()))
            ->setElasticsearchQueryString($index)
            ->__invoke();
        $data = [];
        foreach ($response['suggest']['tag-suggest'][0]['options'] as $hit) {
            $data[] = $hit['_id'];
        }

        return $data;
    }
}
