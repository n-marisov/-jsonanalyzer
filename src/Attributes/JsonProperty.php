<?php

namespace Maris\JsonAnalyzer\Attributes;

use Attribute;
use Maris\JsonAnalyzer\Json;

/**
 * Атрибутом помечаются свойства класса
 * значения которых нужно кодировать и
 * декодировать в json
 */

#[Attribute( Attribute::IS_REPEATABLE | Attribute::TARGET_PROPERTY )]
class JsonProperty
{
    public function __construct(
        /**
         * Ключь в json
         * @var string|null $name
         */
        public ?string $name = null,
        /**
         * Тип данных
         * @var string|null $target
         */
        public ?string $target = null,

        /**
         * Значение по умолчанию в случае если
         * значение не удалось вычеслить
         * @var mixed|null
         */
        public mixed $default = null,

        /**
         * Пространство имен к которому применяется правило
         */
        public string $namespace = Json::DEFAULT_NAMESPACE
    ){}
}