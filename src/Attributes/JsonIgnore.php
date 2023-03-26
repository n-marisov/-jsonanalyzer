<?php

namespace Maris\JsonAnalyzer\Attributes;

use Attribute;
use Maris\JsonAnalyzer\Json;

/**
 * Атрибутом помечается любой компонент класса для ограничения
 * кодирования или декодирования
 */

#[Attribute(Attribute::IS_REPEATABLE| Attribute::TARGET_PROPERTY| Attribute::TARGET_METHOD| Attribute::TARGET_CLASS_CONSTANT)]
readonly class JsonIgnore
{
    public function __construct(
        /**
         * Не приводит к обьекту
         */
        public bool $fromJson = true,
        /**
         * Не приводить к json строке
         * @var bool
         */
        public bool $toJson = true,
        /**
         * Пространство имен к которому применяется правило
         */
        public string $namespace = Json::DEFAULT_NAMESPACE
    ){}
}