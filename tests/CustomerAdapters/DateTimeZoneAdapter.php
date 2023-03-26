<?php

namespace App\Tests\CustomerAdapters;

use DateTimeZone;
use Maris\JsonAnalyzer\Attributes\FromJson;
use Maris\JsonAnalyzer\Attributes\JsonAdapter;
use Maris\JsonAnalyzer\Attributes\ToJson;

#[JsonAdapter(target: DateTimeZone::class,namespace: "SUGGESTIONS")]
#[JsonAdapter(target: DateTimeZone::class,namespace: "CLEAN")]
class DateTimeZoneAdapter
{
    const TIME_ZONES = [
        "UTC+3"=>"Europe/Moscow"
    ];


    #[FromJson(namespace: "SUGGESTIONS")]
    #[FromJson(namespace: "CLEAN")]
    public function from( array $data ):?array
    {
        if(isset($data["timezone"]) && is_string($data["timezone"])){
            $timezone = explode(",",$data["timezone"]);
            foreach ($timezone as $key => $value){
                $timezone[$key] = new DateTimeZone(self::TIME_ZONES[$value]);
            }
            return $timezone;
        }
        return [];
    }

    #[ToJson(namespace: "SUGGESTIONS")]
    #[ToJson(namespace: "CLEAN")]
    public function toString( array $data ):?array
    {
        $result = [];
        /**@var DateTimeZone $value*/
        foreach ($data as $key => $value){
            $result[] = array_search($value->getName(),self::TIME_ZONES);
        }
        return ["timezone" => implode(",", $result )];
    }

}