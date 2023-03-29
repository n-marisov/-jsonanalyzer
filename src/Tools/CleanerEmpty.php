<?php

namespace Maris\JsonAnalyzer\Tools;

/**
 * Очиститель пустых значений
 */

class CleanerEmpty
{
    public static function clear( array &$instance ):void
    {
        foreach ($instance as $key => $value)
            if(self::isEmpty($value)) unset($instance[$key]);
    }

    private static function isEmpty( mixed $value ):bool
    {
        if(in_array($value,["",null,[]])) return true;

        if( is_array($value) ) {
            $isEmpty = true;
            foreach ($value as $item)
                if (!self::isEmpty($item)) $isEmpty = false;
            return $isEmpty;
        }
        return false;
    }
}