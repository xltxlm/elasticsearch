<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/2/16
 * Time: 13:08.
 */

namespace xltxlm\elk\vendor\xltxlm\elasticsearch\src\Tools;

use Overtrue\Pinyin\Pinyin;
use xltxlm\elasticsearch\Unit\ElasticsearchConfig;
use xltxlm\guzzlehttp\PostToJava;

/**
 * 借助elasticsearch进行语句分词
 * Class Analyze.
 */
class Analyze
{
    /** @var ElasticsearchConfig */
    protected $elasticsearchConfig;
    /** @var string 分词协议 */
    protected $tokenizer = "ik_max_word";
    /** @var string 要分词的语句 */
    protected $text = "";

    /**
     * @return ElasticsearchConfig
     */
    public function getElasticsearchConfig(): ElasticsearchConfig
    {
        return $this->elasticsearchConfig;
    }

    /**
     * @param ElasticsearchConfig $elasticsearchConfig
     *
     * @return Analyze
     */
    public function setElasticsearchConfig(ElasticsearchConfig $elasticsearchConfig): Analyze
    {
        $this->elasticsearchConfig = $elasticsearchConfig;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return ($this->text);
    }

    /**
     * @param string $text
     * @return Analyze
     */
    public function setText(string $text): Analyze
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenizer(): string
    {
        return $this->tokenizer;
    }

    /**
     * @param string $tokenizer
     * @return Analyze
     */
    public function setTokenizer(string $tokenizer): Analyze
    {
        $this->tokenizer = $tokenizer;
        return $this;
    }

    /**
     * 返回解析之后的数组
     * @return array
     */
    public function __invoke(): array
    {
        $url = "http://".$this->elasticsearchConfig->getHost().":".$this->elasticsearchConfig->getPort()."/_analyze";
        $tokenizer = (new PostToJava($url))
            ->setBody('
            {
                "tokenizer":"'.$this->getTokenizer().'",
                "text":"'.addslashes($this->getText()).'"
            }
            ')
            ->__invoke();
        $tokenizer = json_decode($tokenizer, true);
        $data = [];
        $data[] = $this->getText();
        $pinyin = new Pinyin();
        foreach ($tokenizer['tokens'] as $item) {
            if (mb_strlen($item['token']) > 1) {
                $data[] = $item['token'];
                $data[] = $pinyin->abbr($item['token']);
                $data[] = $pinyin->sentence($item['token']);
            }
        }
        return array_values(array_unique($data));
    }
}
