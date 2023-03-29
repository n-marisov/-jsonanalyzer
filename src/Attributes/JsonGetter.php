<?php

namespace Maris\JsonAnalyzer\Attributes;

use Attribute;
use Maris\JsonAnalyzer\Json;

/**
 * Атрибутом помечаются методы класса
 * которые возвращают значение для
 * преобразования в json или константы
 */
#[Attribute( Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS_CONSTANT )]
class JsonGetter
{
    public function __construct(
        /**
         * Ключ в json
         * @var string|null $name
         */
        public ?string $name = null,
        /**
         * Пространство имен к которому применяется правило
         */
        public string $namespace = Json::DEFAULT_NAMESPACE
    ){}
}