<?php

namespace Maris\JsonAnalyzer\Tools\Filters;

use ReflectionAttribute;

/**
 * Фильтрует объекты по пространству имен
 */


class NamespaceFilter
{
    protected readonly string $namespace;

    /**
     * @param string $namespace
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @param array<ReflectionAttribute> $attributes
     * @return object|null
     */
    public function filtered ( array $attributes ):?object
    {
        $attributes = array_values( array_filter( $attributes, [$this,"process"] ) );
        return array_pop($attributes)?->newInstance();
    }

    /**
     * @param ReflectionAttribute $attribute
     * @return bool
     */
    public function process( ReflectionAttribute $attribute ):bool
    {
        return $this->namespace === $attribute->newInstance()?->namespace;
    }


}