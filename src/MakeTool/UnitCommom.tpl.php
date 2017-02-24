<?php /** @var \xltxlm\elasticsearch\MakeTool\ElasticsearchMakeToolUnit $this */?>

    /** @var array 查询的内容  */
    protected $__binds = [];
    /** @var array 区间范围  */
    protected $__ranges = [];
    /** @var array 区间范围  */
    protected $__orderby = [];
    /** @var array 被排除的关键词数组  */
    protected $__notin = [];

    /** @var string 模糊检索的字符串  */
    protected $__string = "";

    /** @var ElasticsearchQuery */
    protected $ElasticsearchQuery;

    /**
     * @return ElasticsearchQuery
     */
    public function getElasticsearchQuery(): ElasticsearchQuery
    {
        return $this->ElasticsearchQuery;
    }

    /**
     * 模糊检索,全部字段都查询
     * @return static
     */
    public function where($keyword)
    {
        if($keyword)
        {
                $this->__string = $keyword;
        }
        return $this;
    }

    /**
    * @param string $value
    * @param string $action
    * @param string $explode
    * @param string|bool $timeZone
    * @return static
    */
    public function __where($fieldName, $value,$action=ElasticsearchAction::EQUAL, $explode=" - ",$timeZone=false)
    {
        $value=is_string($value)?trim($value):$value;
        if(empty($value))
        {
            return $this;
        }
        if( in_array( $action , [ ElasticsearchAction::EQUAL ,ElasticsearchAction::LIKE ]) )
        {
            $this->__binds[$fieldName] =
                [
                    'action' => $action,
                    'string' => $value                ];
        }

        if( in_array( $action , [ ElasticsearchAction::MORE ,ElasticsearchAction::LESS, ElasticsearchAction::MOREANDEQUAL, ElasticsearchAction::LESSANDEQUAL ]) )
        {
            $this->__ranges[$fieldName] = sprintf('{ "range":{ "%s":{ "%s":"%s" } } }', $fieldName, $action, $value);
        }
        if( in_array( $action , [ ElasticsearchAction::IN_EQUAL ,ElasticsearchAction::IN_EQUAL ]) )
        {
            list($ltval,$gtval)=explode($explode,$value);
            list($lt,$gt)=explode("|",$action);
            if($timeZone)
            {
                $this->__ranges[$fieldName] = sprintf('{ "range":{ "%s":{ "%s":"%s","%s":"%s","time_zone":"%s" } } }', $fieldName, $lt,$ltval,$gt,$gtval,$timeZone);
            }else
            {
                $this->__ranges[$fieldName] = sprintf('{ "range":{ "%s":{ "%s":"%s","%s":"%s" } } }', $fieldName, $lt,$ltval,$gt,$gtval);
            }
        }
        return $this;
    }

<?php
$Properties = $this->getProperties();
/** @var \ReflectionProperty $property */
foreach ($Properties as $property) {
    if ($property->getName()[0] == '_' && $property->getName()[1] == '_') {
        continue;
    } ?>

    /**
    * @param string $<?=$property->getName()?>

    * @param string $action
    * @param string $explode
    * @param string|bool $timeZone
    * @return static
    */
    public function where<?=ucfirst($property->getName())?>($<?=$property->getName()?>,$action=ElasticsearchAction::EQUAL, $explode=" - ",$timeZone=false)
    {
        $<?=$property->getName()?>=is_string($<?=$property->getName()?>)?trim($<?=$property->getName()?>):$<?=$property->getName()?>;
        if(empty($<?=$property->getName()?>))
        {
            return $this;
        }
        if( in_array( $action , [ ElasticsearchAction::EQUAL ,ElasticsearchAction::LIKE ]) )
        {
            $this->__binds['<?=$property->getName()?>'] =
                [
                    'action' => $action,
                    'string' => $<?=$property->getName()?>
                ];
        }

        if( in_array( $action , [ ElasticsearchAction::MORE ,ElasticsearchAction::LESS, ElasticsearchAction::MOREANDEQUAL, ElasticsearchAction::LESSANDEQUAL ]) )
        {
            $this->__ranges['<?=$property->getName()?>'] = sprintf('{ "range":{ "<?=$property->getName()?>":{ "%s":"%s" } } }',  $action, $<?=$property->getName()?>);
        }
        if( in_array( $action , [ ElasticsearchAction::IN_EQUAL ,ElasticsearchAction::IN_EQUAL ]) )
        {
            list($ltval,$gtval)=explode($explode,$<?=$property->getName()?>);
            list($lt,$gt)=explode("|",$action);
            if($timeZone)
            {
                $this->__ranges['<?=$property->getName()?>'] = sprintf('{ "range":{ "<?=$property->getName()?>":{ "%s":"%s","%s":"%s","time_zone":"%s" } } }',  $lt,$ltval,$gt,$gtval,$timeZone);
            }else
            {
                $this->__ranges['<?=$property->getName()?>'] = sprintf('{ "range":{ "<?=$property->getName()?>":{ "%s":"%s","%s":"%s" } } }',  $lt,$ltval,$gt,$gtval);
            }
        }
        return $this;
    }
    /**
    * @param string $<?=$property->getName()?>

    * @param string $action
    * @return static
    */
    public function where<?=ucfirst($property->getName())?>Notin($<?=$property->getName()?>,$action=ElasticsearchAction::EQUAL)
    {
        $<?=$property->getName()?>=is_string($<?=$property->getName()?>)?trim($<?=$property->getName()?>):$<?=$property->getName()?>;
        if(empty($<?=$property->getName()?>))
        {
            return $this;
        }
        if( in_array( $action , [ ElasticsearchAction::EQUAL ]) )
        {
            foreach( $<?=$property->getName()?> as $item)
            {
                $this->__notin[] = sprintf('{ "%s":{ "<?=$property->getName()?>": "%s" } }',  ElasticsearchAction::EQUAL,$item);
            }
        }
        return $this;
    }

    /**
     * 排序:正序
     * @return static
    */
    public function orderby<?=ucfirst($property->getName())?>Asc()
    {
        $this->__orderby['<?=$property->getName()?>'] = '{"<?=$property->getName()?>" : "asc"}';
        return $this;
    }


    /**
     * 排序:倒序
     * @return static
    */
    public function orderby<?=ucfirst($property->getName())?>Desc()
    {
        $this->__orderby['<?=$property->getName()?>'] = '{"<?=$property->getName()?>" : "desc"}';
        return $this;
    }
<?php
}?>
}