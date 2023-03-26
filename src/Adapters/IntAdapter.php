<?php

namespace Maris\JsonAnalyzer\Adapters;

use Maris\JsonAnalyzer\Attributes\FromJson;
use Maris\JsonAnalyzer\Attributes\JsonAdapter;
use Maris\JsonAnalyzer\Attributes\ToJson;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

#[JsonAdapter(target: "int")]
class IntAdapter implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    #[ToJson]
    public function toInt( ?int $data ):?int
    {
        return $data;
    }

    #[FromJson]
    public function fromInt( string|int|float|bool $data ):?int
    {
        if(is_string($data) && !is_numeric($data)) return null;
        return (int) $data;
    }
}