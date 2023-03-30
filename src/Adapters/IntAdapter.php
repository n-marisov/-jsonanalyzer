<?php

namespace Maris\JsonAnalyzer\Adapters;

use Maris\JsonAnalyzer\Attributes\FromJson;
use Maris\JsonAnalyzer\Attributes\JsonAdapter;
use Maris\JsonAnalyzer\Attributes\ToJson;
use Maris\JsonAnalyzer\Interfaces\JsonAdapterInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

#[JsonAdapter(target: "int")]
class IntAdapter implements JsonAdapterInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct( ?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }
    public function fromJson(mixed $data, string $namespace): ?float
    {
        if (is_object($data) && method_exists($data, "__toString"))
            $data = (string)$data;
        if (!is_numeric($data)) {
            $this->logger->debug("Не является числом");
            return null;
        }
        return (int)$data;
    }

    public function toJson(mixed $data, string $namespace): mixed
    {
        return $data;
    }
}