<?php

use App\Tests\CustomerAdapters\DateTimeZoneAdapter;
use App\Tests\Pojo\Address;
use Maris\JsonAnalyzer\Json;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;


ini_set("display_errors",1);

require_once __DIR__."/../vendor/autoload.php";


$clean = file_get_contents(__DIR__."/Data/clean.json");
$suggestions = file_get_contents(__DIR__."/Data/suggestions.json");

$logger = new Logger("test");
# Отображение ошибок в консоли браузера
$logger->pushHandler(new BrowserConsoleHandler(Level::Debug ));
# Встроенный процессор обрабатывающий плейсхолдеры
$logger->pushProcessor( new PsrLogMessageProcessor() );


Json::initLogger( $logger, ["SUGGESTIONS","CLEAN"] );
Json::registeredAdapter( new DateTimeZoneAdapter(), ["SUGGESTIONS","CLEAN"] );


$suggestions_decode = Json::decode($suggestions,Address::class,"SUGGESTIONS");
$clean_decode = Json::decode($clean,Address::class,"CLEAN");

dump( $suggestions_decode );
dump( $clean_decode );


$suggestions_encode = Json::encode( $suggestions_decode ,"SUGGESTIONS");
$clean_encode = Json::encode( $suggestions_decode ,"CLEAN") ;


dump( json_decode( $suggestions_encode ) );
dump( json_decode( $clean_encode ) );