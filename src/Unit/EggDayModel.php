<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/2/23
 * Time: 15:38.
 */

namespace xltxlm\elk\vendor\xltxlm\elasticsearch\src\Unit;

/**
 * 结果中的日期列表
 * Class EggDayModel.
 */
class EggDayModel
{
    /** @var string 日期 */
    protected $day = '';

    /**
     * @return string
     */
    public function getDay(): string
    {
        return $this->day;
    }

    /**
     * @param string $day
     *
     * @return EggDayModel
     */
    public function setDay(string $day): EggDayModel
    {
        $this->day = $day;

        return $this;
    }
}
