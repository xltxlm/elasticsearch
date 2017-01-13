<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 18:53.
 */

namespace xltxlm\elasticsearch\tests\Resource;

class VideoDemoBody
{
    /** @var string 自增id */
    protected $id = '';
    /** @var string 视频标题 */
    protected $title = '';
    /** @var string 价格 */
    protected $money = '';
    /** @var string 分类 */
    protected $tag = '';

    /**
     * @return string
     */
    public function getMoney(): string
    {
        return $this->money;
    }

    /**
     * @param string $money
     *
     * @return VideoDemoBody
     */
    public function setMoney(string $money): VideoDemoBody
    {
        $this->money = $money;

        return $this;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     *
     * @return VideoDemoBody
     */
    public function setTag(string $tag): VideoDemoBody
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return string
     */
    public function &getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return VideoDemoBody
     */
    public function setId(string $id): VideoDemoBody
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function &getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return VideoDemoBody
     */
    public function setTitle(string $title): VideoDemoBody
    {
        $this->title = $title;

        return $this;
    }
}
