<?php

namespace Maris\JsonAnalyzer\Attributes;
use Attribute;
use Maris\JsonAnalyzer\Json;

/**
 * Атрибутом помечаются свойства и медоды которые должны
 * получить ссылку на родителя для обеспечения навигации
 * по обьекту в стиле DOM дерева.
 */
#[Attribute( Attribute::IS_REPEATABLE | Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD )]
class JsonParent
{
    public function __construct(
        /**
         * Пространство имен к которому применяется правило
         */
        public string $namespace = Json::DEFAULT_NAMESPACE
    ){}
}