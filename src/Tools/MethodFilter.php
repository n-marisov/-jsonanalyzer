<?php

namespace Maris\JsonAnalyzer\Tools;

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