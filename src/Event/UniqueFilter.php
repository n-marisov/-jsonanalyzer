<?php

namespace Maris\JsonAnalyzer\Event;

use Maris\JsonAnalyzer\Tools\EventDispatchers\Event;

/***
 * Событие, которое срабатывает на
 * проверке уникальности объекта.
 * На событии можно модифицировать или
 * заменить объект, который попадет
 * в результирующий массив.
 */
class UniqueFilter extends Event
{
    /**
     * Массив с исходными проверяемыми значениями
     * @var array
     */
    protected static array $originals = [];

    /**
     * Массив с финальными модифицированными значениями
     * @var array
     */
    protected static array $results = [];

    /**
     * Позиция элемента в массивах self::$originals и self::$results
     * @var int
     */
    protected int $position;

    /**
     * Тип объекта
     * @var string
     */
    public readonly string $target;

    /**
     * @param object $instance
     */
    final public function __construct( object $instance )
    {
        $this->target = $instance::class;

        if( !$this->compare( $instance ) ){
            self::$originals[] = $instance;
            self::$results[] = $instance;
        }
        $this->position = array_search( $instance, self::$originals );
    }

    protected function compare( object $instance ):bool
    {
        return in_array( $instance, self::$originals );
    }


    /**
     * Указывает на то что свойство Result изменено
     * @return bool
     */
    public function isModifyResult():bool
    {
        return self::$originals[$this->position] !== self::$results[$this->position];
    }

    /**
     * Возвращает финальный объект который попадает
     * в выборку
     * @return object
     */
    public function getResult():object
    {
        return self::$results[$this->position];
    }

    /**
     * Устанавливает объект результата
     * @param object $instance
     * @return $this
     */
    public function setResult( object $instance ):self
    {
        self::$results[$this->position] = $instance;
        return $this;
    }

    /**
     * Возвращает исходный объект
     * @return object
     */
    public function getOriginal():object
    {
        return self::$originals[$this->position];
    }
}