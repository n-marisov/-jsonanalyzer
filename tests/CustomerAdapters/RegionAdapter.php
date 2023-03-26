<?php

namespace App\Tests\CustomerAdapters;

use App\Tests\Pojo\Component\Region;
use Maris\JsonAnalyzer\Attributes\FromJson;
use Maris\JsonAnalyzer\Attributes\JsonAdapter;
use Maris\JsonAnalyzer\Attributes\ToJson;

#[JsonAdapter(target: Region::class,namespace: "SUGGESTIONS")]
#[JsonAdapter(target: Region::class,namespace: "CLEAN")]
class RegionAdapter
{
    #[FromJson(namespace: "SUGGESTIONS")]
    #[FromJson(namespace: "CLEAN")]
    public function from( array $data ):?Region
    {
        return new Region();
    }

    #[ToJson(namespace: "SUGGESTIONS")]
    #[ToJson(namespace: "CLEAN")]
    public function toString( Region $data ):?string
    {
        return "";
    }
}