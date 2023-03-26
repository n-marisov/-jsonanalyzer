<?php

namespace Maris\JsonAnalyzer\Tools;

/**
 * Требуется для фильтраии значений которые должны
 * буть уникальными в пределах одной сессии
 */

class UniqueFilter
{
    /**
     * @var array<self>
     */
    protected static array $list = [];

    /**
     * Значение которое будет возвращено
     * в случае успеха при сравнения
     * @var object
     */
    protected object $value;

    /***
     * Обьект с которым сравнивается
     * @var object
     */
    protected object $compared;

    /**
     * @param object $value
     * @param object $compared
     */
    private function __construct(object $value, object $compared)
    {
        $this->value = $value;
        $this->compared = $compared;
    }

    /**
     * Сравнивает обьект с текущим
     * @param object $instance
     * @return bool
     */
    public function compare( object $instance ):bool
    {
        return $this->compared == $instance;
    }

    public function getResult():object
    {
        return $this->value;
    }

    public static function add( object $compared, object $value ):void
    {
        self::$list[] = new self( $value, $compared );
    }
    public static function get( object $compared ):false|self
    {
        foreach (self::$list as $filter)
            if($filter->compare($compared)) return $filter;
        return false;
    }
}