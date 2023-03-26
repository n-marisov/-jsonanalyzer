<?php

use App\Tests\CustomerAdapters\DateTimeZoneAdapter;
use App\Tests\Pojo\Address;
use Maris\JsonAnalyzer\Json;
use Maris\JsonAnalyzer\Tools\ObjectAnalyzer;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;


ini_set("display_errors",1);

require_once __DIR__."/../vendor/autoload.php";


$clean = file_get_contents(__DIR__."/Data/clean.json");
$suggestions = file_get_contents(__DIR__."/Data/suggestions.json");



//$analyzerClean = new ObjectAnalyzer("CLEAN");
//$analyzerSuggestions = new ObjectAnalyzer("SUGGESTIONS");

//$analyzerClean->registeredAdapter( new DateTimeZoneAdapter() );
//$analyzerSuggestions->registeredAdapter( new DateTimeZoneAdapter() );

//$clean = "null|";

$logger = new Logger("test");
# Отоброжение ошибок в консоли браузера
$logger->pushHandler(new BrowserConsoleHandler(Level::Debug ));
# Встроеный проесор обрабатывающий плейсхолдеры
$logger->pushProcessor( new PsrLogMessageProcessor() );


echo "<pre>";
Json::initLogger( $logger, ["SUGGESTIONS","CLEAN"] );
Json::registeredAdapter( new DateTimeZoneAdapter(), ["SUGGESTIONS","CLEAN"] );
//Json::registeredAdapter( $logger, ["SUGGESTIONS","CLEAN"] );



$suggestions_decode = Json::decode($suggestions,Address::class,"SUGGESTIONS");
$clean_decode = Json::decode($clean,Address::class,"CLEAN");

dump( $suggestions_decode );
dump( $clean_decode );


$suggestions_encode = Json::encode( $suggestions_decode ,"SUGGESTIONS");
$clean_encode = Json::encode( $suggestions_decode ,"CLEAN") ;


dump( json_decode( $suggestions_encode ) );
dump( json_decode( $clean_encode ) );








/*$fromClean = $analyzerClean->fromJson(json_decode($clean),Address::class);
$fromSuggestions = $analyzerSuggestions->fromJson( json_decode($suggestions),Address::class);


dump($fromClean);

dump($fromSuggestions);

dump($analyzerClean->toJson($fromClean));

dump($analyzerSuggestions->toJson($fromSuggestions));*/


/*unction foo( Iterator&Countable $a ){}

$foo = new ReflectionFunction("foo");

dump($foo->getParameters()[0]->getType());*/

/*//Matrix::addTypeConverter( new DateTimeZoneAdapter() );

Matrix::addAdapter( new DateTimeZoneAdapter() );

$suggestions_decode = Json::decode( json_decode( $suggestions ),Address::class ,"SUGGESTIONS");
$clean_decode = Json::decode( json_decode( $clean ),Address::class ,"CLEAN");

dump($suggestions_decode);
dump($clean_decode);


//dump( Json::encode( $suggestions_decode ,"SUGGESTIONS") );
//dump( Json::encode( $clean_decode ,"CLEAN") );*/