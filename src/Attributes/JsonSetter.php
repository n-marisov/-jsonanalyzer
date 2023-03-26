<?php

namespace Maris\JsonAnalyzer\Attributes;
use Attribute;
use Maris\JsonAnalyzer\Json;

/***
 * Атрибутом помечаются методы класса
 * которые формируют текущий экземплар
 * класса (сетеры)
 */
#[Attribute( Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD )]
class JsonSetter
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
        public string|array|null $target = null,
        /**
         * Пространство имен к которому применяется правило
         */
        public string $namespace = Json::DEFAULT_NAMESPACE
    ){}
}