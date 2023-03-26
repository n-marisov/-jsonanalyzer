<?php

namespace Maris\JsonAnalyzer\Adapters;

use Maris\JsonAnalyzer\Attributes\FromJson;
use Maris\JsonAnalyzer\Attributes\JsonAdapter;
use Maris\JsonAnalyzer\Attributes\ToJson;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

#[JsonAdapter(target: "float")]
class FloatAdapter implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    #[ToJson]
    public function toFloat( ?float $data ):?float
    {
        return $data;
    }

    #[FromJson]
    public function fromFloat( string|int|float|bool|null $data ):?float
    {
        if(is_string($data) && !is_numeric($data)) return null;
        return (float) $data;
    }
}