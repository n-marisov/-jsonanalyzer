<?php

namespace App\Tests\Pojo\Component;


use Maris\JsonAnalyzer\Attributes\JsonObject;
use Maris\JsonAnalyzer\Attributes\JsonProperty;

#[JsonObject(removeEmpty: true,namespace: "SUGGESTIONS")]
#[JsonObject(removeEmpty: true,namespace: "CLEAN")]
class Area
{


    #[JsonProperty(name: "area_fias_id", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "area_fias_id", namespace: "CLEAN")]
    private ?string $fias;


    #[JsonProperty(name: "area_kladr_id", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "area_kladr_id", namespace: "CLEAN")]
    private string $kladr;


    #[JsonProperty(name: "area_with_type", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "area_with_type", namespace: "CLEAN")]
    private string $withType;


    #[JsonProperty(name: "area_type", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "area_type", namespace: "CLEAN")]
    private string $type;


    #[JsonProperty(name: "area_type_full", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "area_type_full", namespace: "CLEAN")]
    private string $typeFull;


    #[JsonProperty(name: "area", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "area", namespace: "CLEAN")]
    private string $name;



}