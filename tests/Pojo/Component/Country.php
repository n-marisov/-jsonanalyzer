<?php

namespace App\Tests\Pojo\Component;

use App\Tests\Pojo\Address;
use Maris\JsonAnalyzer\Attributes\JsonObject;
use Maris\JsonAnalyzer\Attributes\JsonParent;
use Maris\JsonAnalyzer\Attributes\JsonProperty;

#[JsonObject(unique: true, removeEmpty: true, namespace: "SUGGESTIONS")]
#[JsonObject(removeEmpty: true,namespace: "CLEAN")]
class Country
{
   // #[JsonParent(namespace:"SUGGESTIONS")]
    #[JsonParent(namespace:"CLEAN")]
    private Address $parent;


    #[JsonProperty("country", namespace: "SUGGESTIONS")]
    #[JsonProperty("country", namespace: "CLEAN")]
    public string $name;

    #[JsonProperty("country_iso_code", namespace: "SUGGESTIONS")]
    #[JsonProperty("country_iso_code", namespace: "CLEAN")]
    public string $isoCode;


}