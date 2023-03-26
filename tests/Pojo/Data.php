<?php

namespace App\Tests\Data\Model;

use App\Tests\Data\Model\Component\Area;
use App\Tests\Data\Model\Component\City;
use App\Tests\Data\Model\Component\Country;
use App\Tests\Data\Model\Component\Region;
use Location\Coordinate;
use Maris\JsonAnalyzer\Attributes\JsonGetter;
use Maris\JsonAnalyzer\Attributes\JsonIgnore;
use Maris\JsonAnalyzer\Attributes\JsonParent;
use Maris\JsonAnalyzer\Attributes\JsonProperty;
use Maris\JsonAnalyzer\Attributes\JsonSetter;
use stdClass;

class Data
{
    #[JsonProperty(encode: false)]
    public ?Country $country;

    #[JsonProperty]
    private Region $region;

    #[JsonProperty]
    private Area $area;

    #[JsonProperty]
    private City $city;

    private ?Coordinate $coordinate;

    #[JsonParent]
    private Address $parent;

    #[JsonProperty]
    private int $okato;


 /*   #[JsonSetter]
    public function setCoordinate( stdClass|array $coordinate ): self
    {
        //$this->coordinate = new Coordinate($coordinate->geo_lat,$coordinate->geo_lon);
        $this->coordinate = new Coordinate($coordinate["geo_lat"],$coordinate["geo_lon"]);
        return $this;
    }
    #[JsonGetter]
    public function getCoordinate(): ?array
    {
        return [
            "lat"=>$this->coordinate->getLat(),
            "lng"=>$this->coordinate->getLng()
        ];
    }*/


/********************************************************************************/






}