<?php

namespace Maris\JsonAnalyzer\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Throwable;

/**
 * Ошибка контейнера
 */
class ContainerException extends Exception implements ContainerExceptionInterface
{
    use InterpolateTrait;

    public function __construct(string $message = "", array $context = [] , int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct( $this->interpolate($message, $context), $code, $previous );
    }
}