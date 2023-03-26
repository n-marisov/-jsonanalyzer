<?php

namespace App\Tests\Pojo\Component;


use Maris\JsonAnalyzer\Attributes\JsonObject;
use Maris\JsonAnalyzer\Attributes\JsonProperty;
use Maris\JsonAnalyzer\Attributes\ToJson;

#[JsonObject(unique: true, removeEmpty: true, namespace: "SUGGESTIONS")]
#[JsonObject(removeEmpty: true,namespace: "CLEAN")]
class City
{

    #[JsonProperty(name: "city_fias_id", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "city_fias_id", namespace: "CLEAN")]
    private string $fias;

    #[JsonProperty(name: "city_kladr_id", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "city_kladr_id", namespace: "CLEAN")]
    private float $kladr;

    #[JsonProperty(name: "city_with_type", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "city_with_type", namespace: "CLEAN")]
    private string $withType;

    #[JsonProperty(name: "city_type", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "city_type", namespace: "CLEAN")]
    private string $type;

    #[JsonProperty(name: "city_type_full", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "city_type_full", namespace: "CLEAN")]
    private string $typeFull;


    #[JsonProperty(name: "city", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "city", namespace: "CLEAN")]
    private string $name;

    #[JsonProperty(name: "city_area", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "city_area", namespace: "CLEAN")]
    private string $area;

    #[ToJson( namespace:"SUGGESTIONS" )]
    public function toJsonTest():array
    {
        return ["city"=>$this->withType];
    }

}