<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/1/13
 * Time: 11:15.
 */

namespace xltxlm\elasticsearch\MakeTool;

use xltxlm\helper\Hdir\file_put_contents;

/**
 * 最小单元
 * Class ElasticsearchMakeToolUnit.
 */
class ElasticsearchMakeToolUnit
{
    use file_put_contents;
    /** @var ElasticsearchMakeTool */
    protected $ElasticsearchMakeTool;

    /** @var string 需要转换的类 */
    protected $className = '';

    /** @var string 需要转换的类 */
    protected $classShortName = '';

    /** @var string 命名空间 */
    protected $nameSpace = '';

    /** @var string 类文件的路径 */
    protected $filePath = '';

    /** @var \ReflectionProperty[] */
    protected $Properties = [];

    /**
     * @return string
     */
    public function getClassShortName(): string
    {
        return $this->classShortName;
    }

    /**
     * @param string $classShortName
     *
     * @return ElasticsearchMakeToolUnit
     */
    public function setClassShortName(string $classShortName): ElasticsearchMakeToolUnit
    {
        $this->classShortName = $classShortName;

        return $this;
    }

    /**
     * @return ElasticsearchMakeTool
     */
    public function getElasticsearchMakeTool(): ElasticsearchMakeTool
    {
        return $this->ElasticsearchMakeTool;
    }

    /**
     * @param ElasticsearchMakeTool $ElasticsearchMakeTool
     *
     * @return ElasticsearchMakeToolUnit
     */
    public function setElasticsearchMakeTool(ElasticsearchMakeTool $ElasticsearchMakeTool): ElasticsearchMakeToolUnit
    {
        $this->ElasticsearchMakeTool = $ElasticsearchMakeTool;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameSpace(): string
    {
        return $this->nameSpace;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     *
     * @return ElasticsearchMakeToolUnit
     */
    public function setFilePath(string $filePath): ElasticsearchMakeToolUnit
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * @param string $nameSpace
     *
     * @return ElasticsearchMakeToolUnit
     */
    public function setNameSpace(string $nameSpace): ElasticsearchMakeToolUnit
    {
        $this->nameSpace = $nameSpace;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     *
     * @return ElasticsearchMakeToolUnit
     */
    public function setClassName(string $className): ElasticsearchMakeToolUnit
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return \ReflectionProperty[]
     */
    public function getProperties(): array
    {
        return $this->Properties;
    }

    /**
     * @param \ReflectionProperty[] $Properties
     *
     * @return ElasticsearchMakeToolUnit
     */
    public function setProperties(array $Properties): ElasticsearchMakeToolUnit
    {
        $this->Properties = $Properties;

        return $this;
    }

    public function __invoke()
    {
        $dirname = dirname($this->getFilePath());
        $this->file_put_contents($dirname.'/'.$this->getClassShortName().'ElasticsearchQuery.php', __DIR__.'/../MakeTool/ElasticsearchMakeToolUnit.Tpl.php');
    }
}
