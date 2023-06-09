<?php

namespace Maris\JsonAnalyzer\Tools;

use ReflectionAttribute;

/**
 * Фильтрует обьекты по пространству имен
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
        $attributes = array_values( array_filter( $attributes, $this ) );
        return array_pop($attributes)?->newInstance();
    }

    /**
     * @param ReflectionAttribute $attribute
     * @return bool
     */
    public function __invoke( ReflectionAttribute $attribute ):bool
    {
        return $this->namespace === $attribute->newInstance()?->namespace;
    }


}