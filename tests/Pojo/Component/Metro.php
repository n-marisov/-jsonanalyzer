<?php

namespace App\Tests\Pojo\Component;

use Maris\JsonAnalyzer\Attributes\JsonObject;
use Maris\JsonAnalyzer\Attributes\JsonProperty;


#[JsonObject(name: "metro", namespace: "SUGGESTIONS")]
#[JsonObject(name: "metro", namespace: "CLEAN")]
class Metro
{
    #[JsonProperty(name: "distance", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "distance", namespace: "CLEAN")]
    public float $distance;

    #[JsonProperty(name: "line", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "line", namespace: "CLEAN")]
    public string $line;

    #[JsonProperty(name: "name", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "name", namespace: "CLEAN")]
    private string $name;
}