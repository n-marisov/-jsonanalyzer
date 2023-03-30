<?php

namespace Maris\JsonAnalyzer\Exceptions;

trait InterpolateTrait
{
    private function interpolate( string $message, array $context = [] ):string
    {
        $replace = array();
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        return strtr($message, $replace);
    }
}