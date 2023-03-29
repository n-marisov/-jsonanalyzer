<?php

namespace Maris\JsonAnalyzer\Attributes;
use Attribute;
use Maris\JsonAnalyzer\Json;

/**
 * Атрибутом помечается класс, который обрабатывается как сущность
 */

#[Attribute( Attribute::IS_REPEATABLE| Attribute::TARGET_CLASS )]
class JsonObject
{

    public function __construct(
        /**
         * Названия ключа в объекте json
         * по которому будут получены данные
         * для формирования объекта php
         * @var string|null $name
         */
        public readonly ?string $name = null,

        /**
         * Указывает на то что объект
         * должен быть уникальным, т.е
         * не должно быть двух объектов с
         * полностью одинаковым наборам данных
         * и все такие значения по факту будут
         * одним объектом.
         * @var bool
         */
        public readonly bool $unique = false,

        /**
         * Указывает на то что при привидении к json
         * необходимо удалить пустые значения
         * под пустыми значениями, понимаются:
         * null, пустой объект, пустой массив,
         * пустая строка. Числа и логические значения
         * не обрабатываются
         * @var bool
         */
        public readonly bool $removeEmpty = false,

        /**
         * Пространство имен к которому применяется правило
         */
        public readonly string $namespace = Json::DEFAULT_NAMESPACE
    ){}
}