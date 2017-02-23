<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/2/23
 * Time: 14:12.
 */

namespace xltxlm\elk\vendor\xltxlm\elasticsearch\src\Unit;

/**
 * 数据内容 时间 => 个数
 * Class EggModelData.
 */
class EggName2DayModel
{
    /** @var string 维度名称 */
    protected $name = "";
    protected $day = "";
    /** @var string 个数 */
    protected $num = "";

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return EggName2DayModel
     */
    public function setName(string $name): EggName2DayModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDay(): string
    {
        return $this->day;
    }

    /**
     * @param string $day
     * @return EggName2DayModel
     */
    public function setDay(string $day): EggName2DayModel
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @return string
     */
    public function getNum(): string
    {
        return $this->num;
    }

    /**
     * @param string $num
     * @return EggName2DayModel
     */
    public function setNum(string $num): EggName2DayModel
    {
        $this->num = $num;
        return $this;
    }


}
