<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/2/23
 * Time: 13:54
 */

namespace xltxlm\elk\vendor\xltxlm\elasticsearch\src\Unit;


class EggNameModel
{
    /** @var string 日期 */
    protected $name = "";

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return EggNameModel
     */
    public function setName(string $name): EggNameModel
    {
        $this->name = $name;
        return $this;
    }


}