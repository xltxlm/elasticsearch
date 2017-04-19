<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/4/1
 * Time: 13:44.
 */

namespace xltxlm\elasticsearch\Indices;

use xltxlm\elasticsearch\Unit\ElasticsearchConfig;
use xltxlm\guzzlehttp\Get;

/**
 * 索引的列表数据
 * Class IndicesModel.
 */
final class IndicesModel
{
    /** @var  ElasticsearchConfig */
    protected $ElasticsearchConfig;
    /** @var string 健康状态 */
    protected $health = '';
    /** @var string 服务状态 */
    protected $status = '';
    /** @var string 索引名称 */
    protected $index = '';
    /** @var string 唯一id */
    protected $uuid = '';
    /** @var int 行数 */
    protected $docscount = 0;

    /**
     * @return string
     */
    public function getHealth(): string
    {
        return $this->health;
    }

    /**
     * @param string $health
     *
     * @return IndicesModel
     */
    public function setHealth(string $health): IndicesModel
    {
        $this->health = $health;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return IndicesModel
     */
    public function setStatus(string $status): IndicesModel
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @param string $index
     *
     * @return IndicesModel
     */
    public function setIndex(string $index): IndicesModel
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     *
     * @return IndicesModel
     */
    public function setUuid(string $uuid): IndicesModel
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return int
     */
    public function getDocscount(): int
    {
        return $this->docscount;
    }

    /**
     * @param int $docscount
     *
     * @return IndicesModel
     */
    public function setDocscount(int $docscount): IndicesModel
    {
        $this->docscount = $docscount;

        return $this;
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
     * @return IndicesModel
     */
    public function setElasticsearchConfig(ElasticsearchConfig $ElasticsearchConfig): IndicesModel
    {
        $this->ElasticsearchConfig = $ElasticsearchConfig;
        return $this;
    }


    /**
     * @return IndicesModel[]
     */
    public function __invoke(): array
    {
        $indexs = (new Get())
            ->setUrl("http://".$this->getElasticsearchConfig()->getHost().':'.$this->getElasticsearchConfig()->getPort()."/_cat/indices?v")
            ->setUser($this->getElasticsearchConfig()->getUser())
            ->setPasswd($this->getElasticsearchConfig()->getPass())
            ->__invoke();
        $indexs = explode("\n", trim($indexs));
        array_shift($indexs);
        $indexsModel = [];
        foreach ($indexs as $index) {
            $index = preg_split("#\s+#", $index);
            $indexsModel[] = (new IndicesModel())
                ->setElasticsearchConfig($this->getElasticsearchConfig())
                ->setIndex($index[2])
                ->setHealth($index[0])
                ->setStatus($index[1])
                ->setDocscount((int)$index[6]);
        }
        return $indexsModel;
    }
}
