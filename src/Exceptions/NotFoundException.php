<?php

namespace Maris\JsonAnalyzer\Exceptions;

use Exception;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

/**
 * Ошибка отсутствия значения в контейнере
 */
class NotFoundException extends Exception implements NotFoundExceptionInterface
{
    use InterpolateTrait;

    public function __construct(string $message = "", array $context = [] , int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct( $this->interpolate($message, $context), $code, $previous );
    }
}