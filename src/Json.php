<?php

namespace Maris\JsonAnalyzer;

use JsonException;
use Maris\JsonAnalyzer\Tools\JsonDebug;
use Maris\JsonAnalyzer\Tools\ObjectAnalyzer;
use Psr\Log\LoggerInterface;

class Json
{
    const DEFAULT_NAMESPACE = "DEFAULT";

    public static function decode( string $json, string $target, string $namespace = self::DEFAULT_NAMESPACE, int $depth = 512, int $flags = 0 ):array|object|null
    {
        $analyzer = ObjectAnalyzer::get( $namespace );
        try {
            $json = json_decode( $json,false, $depth, $flags|JSON_THROW_ON_ERROR );
        }catch (JsonException){
            $analyzer->getLogger()?->error( JsonDebug::STRING_UNSUITABLE_JSON_DECODE, ["json"=>$json] );
            return null;
        }

        if(!class_exists($target)){
            $analyzer->getLogger()?->error( JsonDebug::TARGET_NOT_CLASS_EXISTS, ["class"=>$target] );
            return null;
        }

        if( is_object($json) || is_array($json) )
        {
            return $analyzer->fromJson( $json, $target );
        }

        $analyzer->getLogger()?->error( JsonDebug::NOT_ARRAY_OR_OBJECT, ["json"=>$json] );
        return null;
    }
    public static function encode( array|object $value, string $namespace = self::DEFAULT_NAMESPACE , int $flags = 0, int $depth = 512):string
    {
        $analyzer = ObjectAnalyzer::get( $namespace );
        $value = $analyzer->toJson($value);

        return json_encode( $value, $flags, $depth );
    }

    /**
     * Устанавливает логер
     * @param LoggerInterface $logger
     * @param string|array $namespaces
     * @return void
     */
    public static function initLogger( LoggerInterface $logger, string|array $namespaces = [Json::DEFAULT_NAMESPACE] ):void
    {
        foreach ( (array) $namespaces as $namespace )
            ObjectAnalyzer::get( $namespace )->setLogger( $logger );
    }

    public static function registeredAdapter(object $adapter, string|array $namespaces = [Json::DEFAULT_NAMESPACE]):void
    {
        foreach ( (array) $namespaces as $namespace )
            ObjectAnalyzer::get( $namespace )->registeredAdapter($adapter);
    }
}