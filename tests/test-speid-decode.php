<?php

use App\Tests\Pojo\Address;
use App\Tests\Pojo\Component\Country;
use App\Tests\Pojo\Component\Region;
use App\Tests\TimingHelper;
use Maris\JsonAnalyzer\Json;

require __DIR__."/../vendor/autoload.php";

$data = file_get_contents(__DIR__."/Data/clean.json");


$helper = new TimingHelper();

$helper->start();
$result = [];
for( $i = 0; $i < 1000 ; $i++)
    $result[] = json_decode($data);

$time_json_decode = $helper->segs();
$memory_json_decode = memory_get_usage() / 1024;

echo "time json_decode : $time_json_decode\n";
echo "memory json_decode: $memory_json_decode kb";
echo "\n\n";
$helper->start();


$helper->start();
$result = [];
$json = [];
for( $i = 0; $i < 1000 ; $i++)
    $json = array_merge( $json,json_decode( $data ) );

    foreach ($json as $key => $item){

        $address = new Address();
        $address->source = $item->source;
        $address->shortValue = $item->result;
        $address->fullValue =  $item->result;
        $address->setLocation((array)$item);

        $country = new Country();
        $country->isoCode = $item->country_iso_code;
        $country->name = $item->country;
        $address->country = $country;

        $region = new Region();
        $region->federalDistrict = $item->federal_district;
        $region->typeFull = $item->region_type_full;
        $region->type = $item->region_type;
        $region->withType = $item->region_with_type;
        $region->name = $item->region;
        $region->iso = $item->region_iso_code;
        $region->kladr = $item->region_kladr_id;
        $region->fias = $item->region_fias_id;
        $address->region = $region;
        $result[] = $address;
}
$time_json_decode_parse = $helper->segs();
$memory_json_decode_parse = memory_get_usage() / 1024;

echo "time json_decode (parse): $time_json_decode_parse\n";
echo "memory json_decode (parse): $memory_json_decode_parse kb" ;
echo "\n\n";
$helper->start();
$result = [];
for( $i = 0; $i < 1000 ; $i++)
    $result[] = Json::decode($data, Address::class,"CLEAN");

$timeJsonDecode = $helper->segs();
$memoryJsonDecode = memory_get_usage() / 1024;

echo "time Json::decode : $timeJsonDecode\n";
echo "memory Json::decode: $memoryJsonDecode kb";
echo "\n\n";

$proc = $time_json_decode / 100;

echo "time Json::decode > json_decode " . ( ($timeJsonDecode - $time_json_decode)/$proc ) ." % \n";

$proc = $time_json_decode_parse / 100;
echo "time Json::decode > json_decode(parse) " . ( ($timeJsonDecode - $time_json_decode_parse)/$proc ) ." %\n";

echo "\n\n";

$proc = $memory_json_decode / 100;

echo "memory Json::decode > json_decode " . ( ($memoryJsonDecode - $memory_json_decode)/$proc ) ." % \n";

$proc = $memory_json_decode_parse / 100;
echo "memory Json::decode > json_decode(parse) " . ( ($memoryJsonDecode - $memory_json_decode_parse)/$proc ) ." %\n";