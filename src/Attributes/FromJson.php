<?php

namespace Maris\JsonAnalyzer\Attributes;

use Attribute;
use Maris\JsonAnalyzer\Json;

/**
 * Атрибутом помечается метод в классе
 * который служит для формирования обьекта
 * для декодирования в случае сущности и
 * преобразовани
 */

#[Attribute( Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD )]
class FromJson
{
    public function __construct(
        /**
         * Пространство имен к которому применяется правило
         */
        public string $namespace = Json::DEFAULT_NAMESPACE
    ){}
}