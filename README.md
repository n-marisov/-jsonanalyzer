# jsonanalyzer
<h2>Библиотека для быстрого преобразования json строки в php объекты.</h2>

---
### Установка
Используя [Composer](https://packagist.org/packages/maris/jsonanalyzer), просто добавьте ее в свой composer.json, выполнив:
<pre><code>composer require maris/jsonanalyzer</code></pre>


---
### Описание 

Библиотека конвертирует json строку сразу в php объекты без необходимости писать парсер.
Для преобразования необходимо разметить модель атрибутами библиотеки.
При этом свойства класса модели может быть не публичным,
а создание экземпляра происходит без вызова конструктора.


---
<h3>Примеры</h3>

<h4>Данные для преобразования взяты из сервиса Dadata поиск адреса</h4>

_<details>_
<summary> Json строка из апи CLEAN (./clean.json) </summary>

```json
[
  {
    "source": "г Ростов-на-Дону, ул Зорге, д 58/3",
    "result": "г Ростов-на-Дону, ул Зорге, д 58/3",
    "postal_code": "344103",
    "country": "Россия",
    "country_iso_code": "RU",
    "federal_district": "Южный",
    "region_fias_id": "f10763dc-63e3-48db-83e1-9c566fe3092b",
    "region_kladr_id": "6100000000000",
    "region_iso_code": "RU-ROS",
    "region_with_type": "Ростовская обл",
    "region_type": "обл",
    "region_type_full": "область",
    "region": "Ростовская",
    "area_fias_id": null,
    "area_kladr_id": null,
    "area_with_type": null,
    "area_type": null,
    "area_type_full": null,
    "area": null,
    "city_fias_id": "c1cfe4b9-f7c2-423c-abfa-6ed1c05a15c5",
    "city_kladr_id": "6100000100000",
    "city_with_type": "г Ростов-на-Дону",
    "city_type": "г",
    "city_type_full": "город",
    "city": "Ростов-на-Дону",
    "city_area": null,
    "city_district_fias_id": null,
    "city_district_kladr_id": null,
    "city_district_with_type": null,
    "city_district_type": null,
    "city_district_type_full": null,
    "city_district": null,
    "settlement_fias_id": null,
    "settlement_kladr_id": null,
    "settlement_with_type": null,
    "settlement_type": null,
    "settlement_type_full": null,
    "settlement": null,
    "street_fias_id": "8f01d820-9097-4431-a33a-e2b1fa01537c",
    "street_kladr_id": "61000001000064000",
    "street_with_type": "ул Зорге",
    "street_type": "ул",
    "street_type_full": "улица",
    "street": "Зорге",
    "house_fias_id": "db3753a7-b70c-4fb0-9c76-2507e339b1cf",
    "house_kladr_id": "6100000100006400086",
    "house_cadnum": "61:44:0071510:74",
    "house_type": "д",
    "house_type_full": "дом",
    "house": "58/3",
    "block_type": null,
    "block_type_full": null,
    "block": null,
    "entrance": null,
    "floor": null,
    "flat_fias_id": null,
    "flat_cadnum": null,
    "flat_type": null,
    "flat_type_full": null,
    "flat": null,
    "flat_area": null,
    "square_meter_price": "86247",
    "flat_price": null,
    "postal_box": null,
    "fias_id": "db3753a7-b70c-4fb0-9c76-2507e339b1cf",
    "fias_code": "61000001000000006400086",
    "fias_level": "8",
    "fias_actuality_state": "0",
    "kladr_id": "6100000100006400086",
    "capital_marker": "2",
    "okato": "60401000000",
    "oktmo": "60701000001",
    "tax_office": "6194",
    "tax_office_legal": "6194",
    "timezone": "UTC+3",
    "geo_lat": "47.2238454",
    "geo_lon": "39.6319512",
    "beltway_hit": null,
    "beltway_distance": null,
    "qc_geo": 0,
    "qc_complete": 5,
    "qc_house": 2,
    "qc": 0,
    "unparsed_parts": null,
    "metro": null
  },
  {
    "source": "г Ростов-на-Дону, ул Зорге, д 58/2 стр 3",
    "result": "г Ростов-на-Дону, ул Зорге, д 58/2 стр 3",
    "postal_code": "344103",
    "country": "Россия",
    "country_iso_code": "RU",
    "federal_district": "Южный",
    "region_fias_id": "f10763dc-63e3-48db-83e1-9c566fe3092b",
    "region_kladr_id": "6100000000000",
    "region_iso_code": "RU-ROS",
    "region_with_type": "Ростовская обл",
    "region_type": "обл",
    "region_type_full": "область",
    "region": "Ростовская",
    "area_fias_id": null,
    "area_kladr_id": null,
    "area_with_type": null,
    "area_type": null,
    "area_type_full": null,
    "area": null,
    "city_fias_id": "c1cfe4b9-f7c2-423c-abfa-6ed1c05a15c5",
    "city_kladr_id": "6100000100000",
    "city_with_type": "г Ростов-на-Дону",
    "city_type": "г",
    "city_type_full": "город",
    "city": "Ростов-на-Дону",
    "city_area": null,
    "city_district_fias_id": null,
    "city_district_kladr_id": null,
    "city_district_with_type": null,
    "city_district_type": null,
    "city_district_type_full": null,
    "city_district": null,
    "settlement_fias_id": null,
    "settlement_kladr_id": null,
    "settlement_with_type": null,
    "settlement_type": null,
    "settlement_type_full": null,
    "settlement": null,
    "street_fias_id": "8f01d820-9097-4431-a33a-e2b1fa01537c",
    "street_kladr_id": "61000001000064000",
    "street_with_type": "ул Зорге",
    "street_type": "ул",
    "street_type_full": "улица",
    "street": "Зорге",
    "house_fias_id": "0c364f16-d7e8-46d3-b2d8-328ba4cb9aa9",
    "house_kladr_id": "6100000100006400313",
    "house_cadnum": null,
    "house_type": "д",
    "house_type_full": "дом",
    "house": "58/2",
    "block_type": "стр",
    "block_type_full": "строение",
    "block": "3",
    "entrance": null,
    "floor": null,
    "flat_fias_id": null,
    "flat_cadnum": null,
    "flat_type": null,
    "flat_type_full": null,
    "flat": null,
    "flat_area": null,
    "square_meter_price": null,
    "flat_price": null,
    "postal_box": null,
    "fias_id": "0c364f16-d7e8-46d3-b2d8-328ba4cb9aa9",
    "fias_code": "61000001000000006400313",
    "fias_level": "8",
    "fias_actuality_state": "0",
    "kladr_id": "6100000100006400313",
    "capital_marker": "2",
    "okato": "60401000000",
    "oktmo": "60701000001",
    "tax_office": "6194",
    "tax_office_legal": "6194",
    "timezone": "UTC+3",
    "geo_lat": "47.2243463",
    "geo_lon": "39.6307382",
    "beltway_hit": null,
    "beltway_distance": null,
    "qc_geo": 1,
    "qc_complete": 5,
    "qc_house": 2,
    "qc": 0,
    "unparsed_parts": null,
    "metro": null
  },
  {
    "source": "мск сухонска 11/-89",
    "result": "г Москва, ул Сухонская, д 11, кв 89",
    "postal_code": "127642",
    "country": "Россия",
    "country_iso_code": "RU",
    "federal_district": "Центральный",
    "region_fias_id": "0c5b2444-70a0-4932-980c-b4dc0d3f02b5",
    "region_kladr_id": "7700000000000",
    "region_iso_code": "RU-MOW",
    "region_with_type": "г Москва",
    "region_type": "г",
    "region_type_full": "город",
    "region": "Москва",
    "area_fias_id": null,
    "area_kladr_id": null,
    "area_with_type": null,
    "area_type": null,
    "area_type_full": null,
    "area": null,
    "city_fias_id": null,
    "city_kladr_id": null,
    "city_with_type": null,
    "city_type": null,
    "city_type_full": null,
    "city": null,
    "city_area": "Северо-восточный",
    "city_district_fias_id": null,
    "city_district_kladr_id": null,
    "city_district_with_type": "р-н Северное Медведково",
    "city_district_type": "р-н",
    "city_district_type_full": "район",
    "city_district": "Северное Медведково",
    "settlement_fias_id": null,
    "settlement_kladr_id": null,
    "settlement_with_type": null,
    "settlement_type": null,
    "settlement_type_full": null,
    "settlement": null,
    "street_fias_id": "95dbf7fb-0dd4-4a04-8100-4f6c847564b5",
    "street_kladr_id": "77000000000283600",
    "street_with_type": "ул Сухонская",
    "street_type": "ул",
    "street_type_full": "улица",
    "street": "Сухонская",
    "house_fias_id": "5ee84ac0-eb9a-4b42-b814-2f5f7c27c255",
    "house_kladr_id": "7700000000028360004",
    "house_cadnum": "77:02:0004008:1017",
    "house_type": "д",
    "house_type_full": "дом",
    "house": "11",
    "block_type": null,
    "block_type_full": null,
    "block": null,
    "entrance": null,
    "floor": null,
    "flat_fias_id": "f26b876b-6857-4951-b060-ec6559f04a9a",
    "flat_cadnum": "77:02:0004008:4143",
    "flat_type": "кв",
    "flat_type_full": "квартира",
    "flat": "89",
    "flat_area": "34.6",
    "square_meter_price": "239953",
    "flat_price": "8302374",
    "postal_box": null,
    "fias_id": "f26b876b-6857-4951-b060-ec6559f04a9a",
    "fias_code": "77000000000000028360004",
    "fias_level": "9",
    "fias_actuality_state": "0",
    "kladr_id": "7700000000028360004",
    "capital_marker": "0",
    "okato": "45280583000",
    "oktmo": "45362000",
    "tax_office": "7715",
    "tax_office_legal": "7715",
    "timezone": "UTC+3",
    "geo_lat": "55.8782557",
    "geo_lon": "37.65372",
    "beltway_hit": "IN_MKAD",
    "beltway_distance": null,
    "qc_geo": 0,
    "qc_complete": 0,
    "qc_house": 2,
    "qc": 0,
    "unparsed_parts": null,
    "metro": [
      {
        "distance": 1.1,
        "line": "Калужско-Рижская",
        "name": "Бабушкинская"
      },
      {
        "distance": 1.2,
        "line": "Калужско-Рижская",
        "name": "Медведково"
      },
      {
        "distance": 2.5,
        "line": "Калужско-Рижская",
        "name": "Свиблово"
      }
    ]
  }
]
```
_</details>_


_<details>_
<summary> Json строка из апи SUGGESTIONS (./suggestions.json)</summary>

```json
    {
  "suggestions": [
    {
      "value": "г Ростов-на-Дону, ул Зорге, д 58/3",
      "unrestricted_value": "344103, Ростовская обл, г Ростов-на-Дону, ул Зорге, д 58/3",
      "data": {
        "postal_code": "344103",
        "country": "Россия",
        "country_iso_code": "RU",
        "federal_district": "Южный",
        "region_fias_id": "f10763dc-63e3-48db-83e1-9c566fe3092b",
        "region_kladr_id": "6100000000000",
        "region_iso_code": "RU-ROS",
        "region_with_type": "Ростовская обл",
        "region_type": "обл",
        "region_type_full": "область",
        "region": "Ростовская",
        "area_fias_id": null,
        "area_kladr_id": null,
        "area_with_type": null,
        "area_type": null,
        "area_type_full": null,
        "area": null,
        "city_fias_id": "c1cfe4b9-f7c2-423c-abfa-6ed1c05a15c5",
        "city_kladr_id": "6100000100000",
        "city_with_type": "г Ростов-на-Дону",
        "city_type": "г",
        "city_type_full": "город",
        "city": "Ростов-на-Дону",
        "city_area": null,
        "city_district_fias_id": null,
        "city_district_kladr_id": null,
        "city_district_with_type": null,
        "city_district_type": null,
        "city_district_type_full": null,
        "city_district": null,
        "settlement_fias_id": null,
        "settlement_kladr_id": null,
        "settlement_with_type": null,
        "settlement_type": null,
        "settlement_type_full": null,
        "settlement": null,
        "street_fias_id": "8f01d820-9097-4431-a33a-e2b1fa01537c",
        "street_kladr_id": "61000001000064000",
        "street_with_type": "ул Зорге",
        "street_type": "ул",
        "street_type_full": "улица",
        "street": "Зорге",
        "stead_fias_id": null,
        "stead_cadnum": null,
        "stead_type": null,
        "stead_type_full": null,
        "stead": null,
        "house_fias_id": "db3753a7-b70c-4fb0-9c76-2507e339b1cf",
        "house_kladr_id": "6100000100006400086",
        "house_cadnum": "61:44:0071510:74",
        "house_type": "д",
        "house_type_full": "дом",
        "house": "58/3",
        "block_type": null,
        "block_type_full": null,
        "block": null,
        "entrance": null,
        "floor": null,
        "flat_fias_id": null,
        "flat_cadnum": null,
        "flat_type": null,
        "flat_type_full": null,
        "flat": null,
        "flat_area": null,
        "square_meter_price": "82748",
        "flat_price": null,
        "room_fias_id": null,
        "room_cadnum": null,
        "room_type": null,
        "room_type_full": null,
        "room": null,
        "postal_box": null,
        "fias_id": "db3753a7-b70c-4fb0-9c76-2507e339b1cf",
        "fias_code": null,
        "fias_level": "8",
        "fias_actuality_state": "0",
        "kladr_id": "6100000100006400086",
        "geoname_id": "501175",
        "capital_marker": "2",
        "okato": "60401000000",
        "oktmo": "60701000001",
        "tax_office": "6194",
        "tax_office_legal": "6194",
        "timezone": "UTC+3",
        "geo_lat": "47.223926",
        "geo_lon": "39.632026",
        "beltway_hit": null,
        "beltway_distance": null,
        "metro": null,
        "divisions": null,
        "qc_geo": "0",
        "qc_complete": null,
        "qc_house": null,
        "history_values": null,
        "unparsed_parts": null,
        "source": null,
        "qc": null,
        "geo_json": {
          "coordinates":[ 37.7,45.5 ]
        }
      }
    },
    {
      "value": "г Ростов-на-Дону, ул Зорге, д 58/2 стр 3",
      "unrestricted_value": "344103, Ростовская обл, г Ростов-на-Дону, ул Зорге, д 58/2 стр 3",
      "data": {
        "postal_code": "344103",
        "country": "Россия",
        "country_iso_code": "RU",
        "federal_district": "Южный",
        "region_fias_id": "f10763dc-63e3-48db-83e1-9c566fe3092b",
        "region_kladr_id": "6100000000000",
        "region_iso_code": "RU-ROS",
        "region_with_type": "Ростовская обл",
        "region_type": "обл",
        "region_type_full": "область",
        "region": "Ростовская",
        "area_fias_id": null,
        "area_kladr_id": null,
        "area_with_type": null,
        "area_type": null,
        "area_type_full": null,
        "area": null,
        "city_fias_id": "c1cfe4b9-f7c2-423c-abfa-6ed1c05a15c5",
        "city_kladr_id": "6100000100000",
        "city_with_type": "г Ростов-на-Дону",
        "city_type": "г",
        "city_type_full": "город",
        "city": "Ростов-на-Дону",
        "city_area": null,
        "city_district_fias_id": null,
        "city_district_kladr_id": null,
        "city_district_with_type": null,
        "city_district_type": null,
        "city_district_type_full": null,
        "city_district": null,
        "settlement_fias_id": null,
        "settlement_kladr_id": null,
        "settlement_with_type": null,
        "settlement_type": null,
        "settlement_type_full": null,
        "settlement": null,
        "street_fias_id": "8f01d820-9097-4431-a33a-e2b1fa01537c",
        "street_kladr_id": "61000001000064000",
        "street_with_type": "ул Зорге",
        "street_type": "ул",
        "street_type_full": "улица",
        "street": "Зорге",
        "stead_fias_id": null,
        "stead_cadnum": null,
        "stead_type": null,
        "stead_type_full": null,
        "stead": null,
        "house_fias_id": "0c364f16-d7e8-46d3-b2d8-328ba4cb9aa9",
        "house_kladr_id": "6100000100006400313",
        "house_cadnum": null,
        "house_type": "д",
        "house_type_full": "дом",
        "house": "58/2",
        "block_type": "стр",
        "block_type_full": "строение",
        "block": "3",
        "entrance": null,
        "floor": null,
        "flat_fias_id": null,
        "flat_cadnum": null,
        "flat_type": null,
        "flat_type_full": null,
        "flat": null,
        "flat_area": null,
        "square_meter_price": null,
        "flat_price": null,
        "room_fias_id": null,
        "room_cadnum": null,
        "room_type": null,
        "room_type_full": null,
        "room": null,
        "postal_box": null,
        "fias_id": "0c364f16-d7e8-46d3-b2d8-328ba4cb9aa9",
        "fias_code": null,
        "fias_level": "8",
        "fias_actuality_state": "0",
        "kladr_id": "6100000100006400313",
        "geoname_id": "501175",
        "capital_marker": "2",
        "okato": "60401000000",
        "oktmo": "60701000001",
        "tax_office": "6194",
        "tax_office_legal": "6194",
        "timezone": "UTC+3",
        "geo_lat": "47.223302",
        "geo_lon": "39.63111",
        "beltway_hit": null,
        "beltway_distance": null,
        "metro": null,
        "divisions": null,
        "qc_geo": "1",
        "qc_complete": null,
        "qc_house": null,
        "history_values": null,
        "unparsed_parts": null,
        "source": null,
        "qc": null
      }
    }
  ]
}
```
_</details>_


### Модель `адрес`

```php
    #[JsonObject(name: "suggestions", namespace: "SUGGESTIONS")]
    #[JsonObject(namespace: "CLEAN")]
    class Address
    {
        #[JsonProperty(name: "source", namespace: "CLEAN")]
        private string $source = '';
    
        #[JsonProperty("result",namespace: "CLEAN")]
        #[JsonProperty(name: "value", namespace: "SUGGESTIONS")]
        private string $shortValue;
    
        #[JsonProperty(name: "unrestricted_value", namespace: "SUGGESTIONS")]
        #[JsonProperty(name:"result",namespace: "CLEAN")]
        private string $fullValue;
    
    
        #[JsonProperty( name: "data", namespace: "SUGGESTIONS" )]
        #[JsonProperty( namespace: "CLEAN" )]
        private Country $country;
    
        #[JsonProperty(name: "data", namespace: "SUGGESTIONS")]
        #[JsonProperty( namespace: "CLEAN")]
        private Region $region;
    }
```

### Модель `страна`

```php
#[JsonObject(unique: true, removeEmpty: true, namespace: "SUGGESTIONS")]
#[JsonObject(removeEmpty: true,namespace: "CLEAN")]
class Country
{
    #[JsonParent(namespace:"CLEAN")]
    private Address $parent;

    #[JsonProperty("country", namespace: "SUGGESTIONS")]
    #[JsonProperty("country", namespace: "CLEAN")]
    private string $name;

    #[JsonProperty("country_iso_code", namespace: "SUGGESTIONS")]
    #[JsonProperty("country_iso_code", namespace: "CLEAN")]
    private string $isoCode;
}
```

### Модель `регион`

```php
    #[JsonObject(unique: true, namespace: "SUGGESTIONS")]
#[JsonObject(unique: true, namespace: "CLEAN")]
class Region
{
    #[JsonProperty(name: "federal_district", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "federal_district", namespace: "CLEAN")]
    private string $federalDistrict;

    #[JsonProperty(name: "region_fias_id", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region_fias_id", namespace: "CLEAN")]
    private bool $fias;

    #[JsonProperty(name: "region_kladr_id", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region_kladr_id", namespace: "CLEAN")]
    private int $kladr;

    #[JsonProperty(name: "region_iso_code", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region_iso_code", namespace: "CLEAN")]
    private string $iso;

    #[JsonProperty(name: "region_with_type", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region_with_type", namespace: "CLEAN")]
    private string $withType;

    #[JsonProperty(name: "region_type", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region_type", namespace: "CLEAN")]
    private string $type;

    #[JsonProperty(name: "region_type_full", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region_type_full", namespace: "CLEAN")]
    private string $typeFull;

    #[JsonProperty(name: "region", namespace: "SUGGESTIONS")]
    #[JsonProperty(name: "region", namespace: "CLEAN")]
    private string $name;
}
```

### Декодирование строки json в объект

```php

$clean = file_get_contents(__DIR__."/clean.json");
$suggestions = file_get_contents(__DIR__."/suggestions.json");

$suggestions_decode = Json::decode($suggestions,Address::class,"SUGGESTIONS");
$clean_decode = Json::decode($clean,Address::class,"CLEAN");

echo "<pre>";

print_r($clean_decode);
print_r($suggestions_decode);
    
```

### Кодирование объекта или массива объектов в json строку

```php

$suggestions_encode = Json::encode( $suggestions_decode ,"SUGGESTIONS");
$clean_encode = Json::encode( $suggestions_decode ,"CLEAN") ;

print_r($suggestions_encode);
print_r($clean_encode);
```

