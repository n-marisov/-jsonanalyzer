<?php

namespace App\Tests\Pojo\Component;


use Maris\JsonAnalyzer\Attributes\JsonObject;
use Maris\JsonAnalyzer\Attributes\JsonProperty;

#[JsonObject(unique: true, namespace: "SUGGESTIONS")]
#[JsonObject(unique: true, namespace: "CLEAN")]
class Region
{

    #[JsonProperty(name: "federal_district", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "federal_district", namespace: "CLEAN")]
    public string $federalDistrict;


    #[JsonProperty(name: "region_fias_id", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region_fias_id", namespace: "CLEAN")]
    public bool $fias;


    #[JsonProperty(name: "region_kladr_id", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region_kladr_id", namespace: "CLEAN")]
    public int $kladr;

    #[JsonProperty(name: "region_iso_code", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region_iso_code", namespace: "CLEAN")]
    public string $iso;


    #[JsonProperty(name: "region_with_type", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region_with_type", namespace: "CLEAN")]
    public string $withType;


    #[JsonProperty(name: "region_type", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region_type", namespace: "CLEAN")]
    public string $type;


    #[JsonProperty(name: "region_type_full", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region_type_full", namespace: "CLEAN")]
    public string $typeFull;


    #[JsonProperty(name: "region", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region", namespace: "CLEAN")]
    public string $name;



}