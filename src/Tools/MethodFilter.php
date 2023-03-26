<?php

namespace Maris\JsonAnalyzer\Tools;

use Maris\JsonAnalyzer\Attributes\FromJson;
use Maris\JsonAnalyzer\Attributes\ToJson;
use ReflectionClass;
use ReflectionMethod;

/**
 * Ищет метод по наличию в нем атрибута
 * в определенном пространстве имен
 */

class MethodFilter
{
    protected NamespaceFilter $filter;

    /**
     * @param NamespaceFilter $filter
     */
    public function __construct( NamespaceFilter $filter )
    {
        $this->filter = $filter;
    }

    public function filtered( ReflectionClass $class , ?object $attr ):?ReflectionMethod
    {
        if(is_null($attr)) return null;
        foreach ( $class->getMethods() as $method ){
            $attributes = $method->getAttributes();
            $attribute = $this->filter->filtered( $attributes );
            if($attr == $attribute)return $method;
        }
        return null;
    }

    public function search( ReflectionClass $class , string $attrClass ):?ReflectionMethod
    {
        foreach ( $class->getMethods() as $method ){
            $attributes = $method->getAttributes( $attrClass );
            $attribute = $this->filter->filtered( $attributes );
            if(isset( $attribute ))return $method;
        }
        return null;
    }
}