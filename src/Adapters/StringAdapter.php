<?php

namespace Maris\JsonAnalyzer\Adapters;


use Maris\JsonAnalyzer\Attributes\JsonAdapter;
use Maris\JsonAnalyzer\Interfaces\JsonAdapterInterface;
use Maris\JsonAnalyzer\Tools\JsonDebug;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

#[JsonAdapter(target: "string")]
class StringAdapter implements JsonAdapterInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;


    public function __construct( ?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function fromJson(mixed $data, string $namespace): ?string
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

    public function toJson(mixed $data, string $namespace): mixed
    {
        return $data;
    }
}