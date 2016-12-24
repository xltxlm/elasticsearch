<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2016/12/24
 * Time: 18:53.
 */

namespace xltxlm\elasticsearch\tests\Resource;

use xltxlm\elasticsearch\Unit\ElasticsearchModel;

class VideoDemoBody extends ElasticsearchModel
{
    /** @var string 自增id */
    protected $id = '';
    /** @var string 视频标题 */
    protected $title = '';

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
