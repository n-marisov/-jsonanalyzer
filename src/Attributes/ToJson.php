<?php

namespace Maris\JsonAnalyzer\Attributes;

use Attribute;
use Maris\JsonAnalyzer\Json;

/**
 * Атрибутом помечается метод класса
 * который приводит обьект к массиву
 * для подготовки к json_encode
 * в случае адаптора должен вернуть
 * значение
 */

#[Attribute( Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD )]
class ToJson
{
    public function __construct(
        /**
         * Пространство имен к которому применяется правило
         */
        public string $namespace = Json::DEFAULT_NAMESPACE
    ){}
}