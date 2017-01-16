<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/1/13
 * Time: 11:27
 */

namespace xltxlm\elasticsearch\Unit;


class ElasticsearchAction
{
    const EQUAL = "term";
    const LIKE = "match";
    const MORE = "gt";
    const LESS = "lt";
    const MOREANDEQUAL = "gte";
    const LESSANDEQUAL = "lte";
    //区间计算
    const IN_EQUAL = "gte|lte";
    const IN_EQUAL_LESS = "gt|lte";
    const IN_EQUAL_MORE = "gte|lt";
    const IN = "gt|lt";
}