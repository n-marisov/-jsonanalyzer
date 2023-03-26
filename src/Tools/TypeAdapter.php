<?php

namespace Maris\JsonAnalyzer\Tools;

use Maris\JsonAnalyzer\Attributes\FromJson;
use Maris\JsonAnalyzer\Attributes\JsonAdapter;
use Maris\JsonAnalyzer\Attributes\ToJson;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

readonly class TypeAdapter
{
    private ObjectAnalyzer $analyzer;
    public ?JsonAdapter $adapter;

    public object $handler;

    public ?ReflectionMethod $toJson;

    public ?ReflectionMethod $fromJson;

    /**
     * @param object $handler
     * @param ObjectAnalyzer|null $analyzer
     * @param JsonAdapter|null $adapter
     * @param ReflectionClass|null $reflectionHandler
     */
    public function __construct( object $handler , ?ObjectAnalyzer $analyzer = null , ?JsonAdapter $adapter = null , ?ReflectionClass $reflectionHandler = null)
    {
        $this->handler = $handler;
        $this->adapter = $adapter;
        $this->analyzer = $analyzer;
        if(isset($adapter) && isset($reflectionHandler) && $reflectionHandler->name === $handler::class){
            $methodFilter = new MethodFilter($analyzer->namespaceFilter);
            $this->toJson = $methodFilter->search( $reflectionHandler,ToJson::class );
            $this->fromJson = $methodFilter->search( $reflectionHandler,FromJson::class );
        }else{
            $this->toJson = null;
            $this->fromJson = null;
        }
    }


    public function isAdapter():bool
    {
        if(!isset($this->toJson)){
            $this->analyzer->getLogger()?->warning(JsonDebug::NOT_METHOD_ADAPTER,[
                    "class" => $this->adapter::class,
                    "method" => "toJson",
                    "namespace"=>$this->adapter->namespace
                ]);
            return false;
        }
        if(!isset($this->fromJson)){
            $this->analyzer->getLogger()?->warning(JsonDebug::NOT_METHOD_ADAPTER,[
                    "class" => $this->adapter::class,
                    "method" => "fromJson",
                    "namespace"=>$this->adapter->namespace
                ]);
            return false;
        }

        if( in_array($this->adapter->target,["string","bool","float","int"]) || class_exists($this->adapter->target) ){
            return true;
        }

        $this->analyzer->getLogger()?->warning(JsonDebug::TARGET_INVALID_ADAPTER,[
                "target" => $this->adapter->target,
                "namespace"=>$this->adapter->namespace
            ]);
        return false;
    }
    public function getTarget():string
    {
        return $this->adapter->target;
    }



    public function fromJson( mixed $data ):mixed
    {
        if(!isset($this->fromJson)) return null;
        return $this->fromJson->invoke($this->handler,$data);
    }

    public function toJson( mixed $data ):mixed
    {
        if(!isset($this->toJson)) return null;
        return $this->toJson->invoke($this->handler,$data);
    }


    /**
     * Формирует адаптер по умолчанию
     * @param object $adapter
     * @return static|null
     * @throws ReflectionException
     */
    public static function newDefaultInstance( object $adapter ):?self
    {
        $class = new ReflectionClass(self::class);
        $adapterClass = new ReflectionClass( $adapter );
        $instance = $class->newInstanceWithoutConstructor();
        $class->getProperty("handler")->setValue( $instance, $adapter );
        $attr = $adapterClass->getAttributes(JsonAdapter::class);
        if(empty($attr)) return null;
        $class->getProperty("adapter")->setValue( $instance, $attr[0]->newInstance() );

        foreach ( $adapterClass->getMethods() as $method ){
            $attr = $method->getAttributes(FromJson::class);
            if(!empty($attr)){
                $class->getProperty("fromJson")->setValue($instance,$method);
                continue;
            }
            $attr = $method->getAttributes(ToJson::class);
            if(!empty($attr)){
                $class->getProperty("toJson")->setValue($instance,$method);
            }
        }
        return $instance;
    }

}