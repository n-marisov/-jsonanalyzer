<?php

namespace Maris\JsonAnalyzer\Adapters;


use Maris\JsonAnalyzer\Interfaces\JsonAdapterInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

class FloatAdapter implements JsonAdapterInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct( ?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }
    public function fromJson(mixed $data, string $namespace): ?float
    {
        if(is_object($data) && method_exists($data,"__toString"))
            $data = (string)$data;
        if(!is_numeric($data)){
            $this->logger->debug("Не является числом");
            return null;
        }
        return (float)$data;
    }

    public function toJson(mixed $data, string $namespace): mixed
    {
        return $data;
    }
}