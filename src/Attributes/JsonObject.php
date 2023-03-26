<?php

namespace Maris\JsonAnalyzer\Attributes;
use Attribute;
use Maris\JsonAnalyzer\Json;

/**
 * Атрибутом помечается класс который обработывается как сущность
 */

#[Attribute( Attribute::IS_REPEATABLE| Attribute::TARGET_CLASS )]
readonly class JsonObject
{

    public function __construct(
        /**
         * Названия ключа в объекте json
         * по которому будут получены данные
         * для формирования обьекта php
         * @var string|null $name
         */
        public ?string $name = null,

        /**
         * Указывает на то что обьект
         * должен быть уникальным , т.е
         * Не должно быть двух обьектов с
         * полностью одинаковым наборам данных
         * и все такие значения по факту будут
         * одним обьектом
         * @var bool
         */
        public bool $unique = false,

        /**
         * Указывает на то что при привидении к json
         * необходимо удалить пустые значения
         * под пустыми значениями понимается
         * null , пустой обьект , пустой массив,
         * пустая строка. Числа и логические значения
         * не обрабатываются
         * @var bool
         */
        public bool $removeEmpty = false,

        /**
         * Пространство имен к которому применяется правило
         */
        public string $namespace = Json::DEFAULT_NAMESPACE
    ){}
}