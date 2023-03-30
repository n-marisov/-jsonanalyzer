<?php


use App\Tests\CustomerAdapters\DateTimeZoneAdapter;
use App\Tests\Pojo\Address;
use Maris\JsonAnalyzer\Analyzer;
use Maris\JsonAnalyzer\Event\UniqueFilter;
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



$analyzerSuggestions = new Analyzer("SUGGESTIONS");
$analyzerClean = new Analyzer("CLEAN");

$analyzerSuggestions->registeredAdapter(DateTimeZone::class , new DateTimeZoneAdapter() );
$analyzerClean->registeredAdapter(DateTimeZone::class , new DateTimeZoneAdapter() );

$analyzerSuggestions->setLogger($logger);
$analyzerClean->setLogger($logger);


function uniqueFilter ( UniqueFilter $event ): void
{
   if(!$event->isModifyResult())
       $event->setResult( $event->getOriginal() );
}

$analyzerSuggestions->addEventListener(UniqueFilter::class,"uniqueFilter");
$analyzerClean->addEventListener(UniqueFilter::class,"uniqueFilter");



$fromSuggestions = $analyzerSuggestions->fromArray(json_decode( $suggestions ,1),Address::class);
$fromClean = $analyzerClean->fromArray(json_decode( $clean ),Address::class);

dump( $fromSuggestions );
dump( $fromClean );

/*dump($analyzerSuggestions->toArray($fromSuggestions));
dump($analyzerClean->toArray($fromClean));


dump(Json::decode($suggestions,Address::class,"SUGGESTIONS"));
dump(Json::encode($fromSuggestions,"SUGGESTIONS"));*/


/*$dispatcher = new Dispatcher();
$dispatcher->provider->addListener(UniqueFilter::class , function ( UniqueFilter $event ){
    if(!$event->isModifyResult())
        $event->setResult( new stdClass() );
});


dump( $dispatcher->dispatch( new UniqueFilter( new Address() ) )->getResult() );*/