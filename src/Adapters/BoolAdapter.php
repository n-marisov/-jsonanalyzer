<?php

namespace Maris\JsonAnalyzer\Adapters;

use Maris\JsonAnalyzer\Attributes\FromJson;
use Maris\JsonAnalyzer\Attributes\JsonAdapter;
use Maris\JsonAnalyzer\Attributes\ToJson;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

#[JsonAdapter(target: "bool")]
class BoolAdapter implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    #[ToJson]
    public function toBool( ?bool $data ):?bool
    {
        return $data;
    }

    #[FromJson]
    public function fromBool( string|int|float|array|object $data ):bool
    {
        return (bool) $data;
    }
}