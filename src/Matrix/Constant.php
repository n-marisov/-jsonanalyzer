<?php

namespace Maris\JsonAnalyzer\Matrix;

use JetBrains\PhpStorm\Pure;
use Maris\JsonAnalyzer\Analyzer;
use Maris\JsonAnalyzer\Attributes\JsonGetter;
use Maris\JsonAnalyzer\Attributes\JsonIgnore;
use ReflectionClassConstant;

class Constant extends ReflectionClassConstant
{
    /**
     * Родительский анализатор
     * @var Analyzer
     */
    public Analyzer $analyzer;
    /**
     * Атрибут
     * @var JsonIgnore|null
     */
    protected ?JsonIgnore $ignore;

    /**
     * Атрибут
     * @var JsonGetter|null
     */
    protected ?JsonGetter $jsonGetter;

    public function __construct( ReflectionClassConstant $constant, Analyzer $analyzer )
    {
        parent::__construct( $constant->class, $constant->name );

        $this->analyzer = $analyzer;
        $this->ignore = $analyzer->namespaceFilter->filtered($this->getAttributes(JsonIgnore::class));
        $this->jsonGetter = $analyzer->namespaceFilter->filtered($this->getAttributes(JsonGetter::class));
    }

    /**
     * Указывает на наличие гетера
     * @return bool
     */
    public function isGetter():bool
    {
        return isset($this->jsonGetter);
    }

    /**
     * Определяет нужно ли печатать объект
     * @param bool|null $from
     * @param bool|null $to
     * @return bool
     */
    public function isIgnore( ?bool $from = null, ?bool $to = null):bool
    {
        # Значит атрибутом не помечен
        if(!isset($this->ignore)) return false;
        return $from !== $this->ignore->fromJson || $to !== $this->ignore->toJson;
    }

    #[Pure]
    public function getValue(): mixed
    {
        return parent::getValue();
    }

    public function getJsonName():string
    {
        return $this->jsonGetter->name ?? $this->getName();
    }

}