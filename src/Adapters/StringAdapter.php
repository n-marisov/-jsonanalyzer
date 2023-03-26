<?php

namespace Maris\JsonAnalyzer\Adapters;

use Maris\JsonAnalyzer\Attributes\FromJson;
use Maris\JsonAnalyzer\Attributes\JsonAdapter;
use Maris\JsonAnalyzer\Attributes\ToJson;
use Maris\JsonAnalyzer\Tools\JsonDebug;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

#[JsonAdapter(target: "string")]
class StringAdapter implements LoggerAwareInterface
{
    use LoggerAwareTrait;
    #[ToJson]
    public function toString( ?string $data ):?string
    {
        return $data;
    }

    #[FromJson]
    public function fromString( mixed $data ):?string
    {
        if(is_object($data)){
            if(!method_exists($data,"__toString")){
                $this->logger?->debug(JsonDebug::OBJECT_NOT_TO_STRING,[
                    "class"=>$data::class,
                    "data"=>$data
                ]);
                return null;
            }
        }
        elseif (is_array($data)) return json_encode($data);
        return (string) $data;
    }
}