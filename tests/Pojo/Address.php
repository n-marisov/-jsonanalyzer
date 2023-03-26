<?php

namespace App\Tests\Pojo;


use App\Tests\Pojo\Component\Area;
use App\Tests\Pojo\Component\City;
use App\Tests\Pojo\Component\Country;
use App\Tests\Pojo\Component\Metro;
use App\Tests\Pojo\Component\Region;
use DateTimeZone;
use Location\Coordinate;
use Location\Polygon;
use Maris\JsonAnalyzer\Attributes\JsonGetter;
use Maris\JsonAnalyzer\Attributes\JsonIgnore;
use Maris\JsonAnalyzer\Attributes\JsonObject;
use Maris\JsonAnalyzer\Attributes\JsonParent;
use Maris\JsonAnalyzer\Attributes\JsonProperty;
use Maris\JsonAnalyzer\Attributes\JsonSetter;


#[JsonObject(name: "suggestions", namespace: "SUGGESTIONS")]
#[JsonObject(namespace: "CLEAN")]
class Address
{
    #[JsonIgnore(namespace: "SUGGESTIONS")]
    #[JsonIgnore(namespace: "CLEAN")]
    #[JsonGetter(name:"version",namespace: "SUGGESTIONS")]
    #[JsonGetter(name:"version",namespace: "CLEAN")]
    const VERSION = 1;

   // #[JsonIgnore(namespace: "CLEAN",fromJson: true)]
    #[JsonProperty(name: "source", namespace: "CLEAN")]
    public string $source;


    #[JsonProperty("result",namespace: "CLEAN")]
    #[JsonProperty(name: "value", namespace: "SUGGESTIONS")]
    public string $shortValue;

    #[JsonProperty(name: "unrestricted_value", namespace: "SUGGESTIONS")]
    #[JsonProperty(name:"result",namespace: "CLEAN")]
    public string $fullValue;


   #[JsonProperty( name: "data", namespace: "SUGGESTIONS" )]
   #[JsonProperty( namespace: "CLEAN" )]
    public Country $country;

    #[JsonProperty(name: "data", namespace: "SUGGESTIONS")]
    #[JsonProperty( namespace: "CLEAN")]
    public Region $region;
/*
    #[JsonProperty(name: "data", namespace: "SUGGESTIONS")]
    #[JsonProperty( namespace: "CLEAN")]
    private Area $area;

    #[JsonProperty(name: "data", namespace: "SUGGESTIONS")]
    #[JsonProperty(namespace: "CLEAN")]
    private City $city;


   #[JsonProperty( name: "data", target: DateTimeZone::class, namespace: "SUGGESTIONS")]
   #[JsonProperty( target: DateTimeZone::class, namespace: "CLEAN" )]
    private array $timezone;
*/
    private ?Coordinate $location;

    /**
     * Если $target не указан то принимает
     * весь массив с исходными данными
     * @param array $data
     * @return $this
     */
    #[JsonSetter( name: "data", namespace: "SUGGESTIONS" )]
    #[JsonSetter( namespace: "CLEAN")]
    public function setLocation( Polygon|Coordinate|array $data ): self
    {
        //$this->location = new Coordinate( $data->geo_lat, $data->geo_lon );
        if(isset($data["qc_geo"]) && $data["qc_geo"] < 5 ){
            $this->location = new Coordinate($data["geo_lat"],$data["geo_lon"]);
        }else $this->location = null;
        return $this;
    }

    /**
     * @return Coordinate|null
     */
 /*   #[JsonGetter(name: "data",namespace: "SUGGESTIONS")]
    #[JsonGetter(namespace: "CLEAN")]
    private function getLocationJson(): ?array
    {
        if(empty($this->location)) return null;
        return [
            "geo_lat"=>$this->location->getLat(),
            "geo_lon"=>$this->location->getLng()
        ];
    }




    /**
     * Если $target указан и тип данных массив
     * и тип данных в json массив то возврощает
     * массив обьектов $target[]
     * @param array $metro
     * @param Region $region
     * @return $this
     */
 /*   #[JsonSetter(name: "data", target: [ null, Metro::class ], namespace: "SUGGESTIONS")]
    #[JsonSetter( target: [ "metro" => Metro::class ], namespace: "CLEAN")]
    public function setMetro( Region $region, array $metro  ): self
    {
       /// dump( $region, $metro );
        $this->metro = $metro;
        return $this;
    }


    /*#[Json(key: "data", target: [ Metro::class ], id: "SUGGESTIONS")]
    #[Json( target: [ "metro" => Metro::class ], id: "CLEAN")]
    public function setMetro( array $metro ): self
    {
        $this->metro = $metro;
        return $this;
    }*/



}