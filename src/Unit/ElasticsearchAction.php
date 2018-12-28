<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/1/13
 * Time: 11:27.
 */

namespace xltxlm\elasticsearch\Unit;

use xltxlm\ormTool\Template\PdoAction;

class ElasticsearchAction
{
    const EQUAL = 'term';
    const LIKE = 'match_phrase';
    const LIKE_NUMBER = 'wildcard';
    const MORE = 'gt';
    const MOREANDEQUAL = 'gte';
    const LESS = 'lt';
    const LESSANDEQUAL = 'lte';
    //区间计算
    const IN_EQUAL = 'gte|lte';
    const IN_EQUAL_LESS = 'gt|lte';
    const IN_EQUAL_MORE = 'gte|lt';
    const IN = 'gt|lt';
    const INJSON = 'terms';
    const INLIST = " inlist ";
    const NOTINJSON = 'notterms';
    const NOTEQUAL = '!=';
    const NOTEMPTY = PdoAction::NOTEMPTY;
    const EMPTY = PdoAction::EMPTY;

    const PdoAction = [
        PdoAction::MORE => self::MORE,
        PdoAction::MOREANDEQUAL => self::MOREANDEQUAL,
        PdoAction::LESS => self::LESS,
        PdoAction::LESSANDEQUAL => self::LESSANDEQUAL,
        PdoAction::EQUAL => self::EQUAL,
        PdoAction::LIKE => self::LIKE,
        PdoAction::NOTINJSON => self::NOTINJSON,
        PdoAction::INJSON => self::INJSON,
        PdoAction::NOTEMPTY => self::NOTEMPTY,
        PdoAction::EMPTY => self::EMPTY,
    ];
}
