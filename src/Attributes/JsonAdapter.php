<?php

namespace Maris\JsonAnalyzer\Attributes;

use Attribute;
use Maris\JsonAnalyzer\Json;

/**
 * Атрибутом помечается класс который, является адаптером
 * Класс должен иметь минимум два метода
 * помеченных атрибутами #[ToJson] и #[FromJson]
 */
#[Attribute( Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS )]
class JsonAdapter
{
    public function __construct(

        /**
         * Тип данных который обрабатывает адаптер
         */
        public string $target,

        /**
         * Пространство имен к которому применяется правило
         */
        public string $namespace = Json::DEFAULT_NAMESPACE
    ){}
}