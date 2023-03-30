<?php

namespace Maris\JsonAnalyzer\Adapters;

use Maris\JsonAnalyzer\Attributes\FromJson;
use Maris\JsonAnalyzer\Attributes\JsonAdapter;
use Maris\JsonAnalyzer\Attributes\ToJson;
use Maris\JsonAnalyzer\Interfaces\JsonAdapterInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

class BoolAdapter implements JsonAdapterInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct( ?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }
    public function fromJson( mixed $data, string $namespace ): bool
    {
        return (bool) $data;
    }

    public function toJson(mixed $data, string $namespace): bool
    {
        return $data;
    }
}