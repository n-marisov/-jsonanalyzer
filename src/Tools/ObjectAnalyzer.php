<?php

namespace Maris\JsonAnalyzer\Tools;

use Maris\JsonAnalyzer\Adapters\BoolAdapter;
use Maris\JsonAnalyzer\Adapters\FloatAdapter;
use Maris\JsonAnalyzer\Adapters\IntAdapter;
use Maris\JsonAnalyzer\Adapters\StringAdapter;
use Maris\JsonAnalyzer\Attributes\JsonAdapter;
use Maris\JsonAnalyzer\Json;
use Maris\JsonAnalyzer\Matrix\Matrix;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use ReflectionClass;

/**
 * Главный рабочий класс
 *
 */
class ObjectAnalyzer implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Хранит с себе все созданные матрицы
     * array< namespace , self >
     * @var array< string, self >
     */
    protected static array $instances = [];

    /***
     * Хранит с себе все адаптеры для преобразований
     * значений
     * @var array< string|class-string, TypeAdapter >
     */
    protected array $adapters = [];

    /**
     * Пространство имен
     * @var string
     */
    public readonly string $namespace;

    /**
     * Хранит в себе все созданные матрицы
     * @var array< class-string, Matrix >
     */
    protected array $matrix = [];

    /**
     * Фильтр для фильтрации по пространству имен
     * @var NamespaceFilter
     */
    public readonly NamespaceFilter $namespaceFilter;



    private function __construct( string $namespace = Json::DEFAULT_NAMESPACE )
    {
        $this->namespace = $namespace;
        $this->namespaceFilter = new NamespaceFilter( $namespace );
    }

    /**
     * @param object|array $data
     * @param class-string $target
     * @param object|null $parent
     * @return array|object|null
     */
    public function fromJson(object|array $data , string $target , ?object $parent = null):array|object|null
    {
        if(!isset($this->matrix[$target]))$this->matrix[$target] = new Matrix( $target, $this );
        $matrix = $this->matrix[$target];

        $start = $matrix->getStartJsonKey();

        if(isset($start)){
            if(!is_object($data)){
                $this->logger?->debug(JsonDebug::SEARCH_KEY_NOT_OBJECT,[
                    "key"=>$start,
                    "data"=>$data
                ]);
                return null;
            }elseif (!property_exists( $data, $start )){
                dump( $data->{$start});
                $this->logger?->debug(JsonDebug::OBJECT_NOT_KEY_EXIST,[
                    "key"=>$start,
                    "data"=>$data
                ]);
                return null;
            }elseif (!isset($data->{$start})){
                $this->logger?->debug(JsonDebug::NOT_DATA_OBJECT_IN_KEY,[
                    "key"=>$start,
                    "data"=>$data
                ]);
                return null;
            }else return $this->formJsonProcess( $data->{$start}, $matrix , $parent);
        }
        return $this->formJsonProcess( $data, $matrix , $parent);
    }

    protected function formJsonProcess( array|object $data , Matrix $matrix, ?object $parent = null):array|object
    {

        if(is_object($data)){
            return $matrix->fromJson( $data , $parent );
        }
        foreach ($data as $key => $value){
            $data[$key] = $this->formJsonProcess($value, $matrix, $parent);
        }
        return $data;
    }

    /**
     * Возвращает адаптер
     * @param string $type
     * @return TypeAdapter|null
     */
    public function getTypeAdapter( string $type ):?TypeAdapter
    {
        if($type === "string" && !isset($this->adapters[$type])){
            $adapter = new StringAdapter();
            if( isset($this->logger) && is_a($adapter,LoggerAwareInterface::class) )
                $adapter->setLogger( $this->logger );
            $this->adapters[$type] = TypeAdapter::newDefaultInstance( $adapter );
        }
        if($type === "float" && !isset($this->adapters[$type])){
            $adapter = new FloatAdapter();
            if(isset($this->logger) && is_a($adapter,LoggerAwareInterface::class))
                $adapter->setLogger( $this->logger );
            $this->adapters[$type] = TypeAdapter::newDefaultInstance( $adapter );
        }
        if($type === "int" && !isset($this->adapters[$type])){
            $adapter = new IntAdapter();
            if(isset($this->logger) && is_a($adapter,LoggerAwareInterface::class))
                $adapter->setLogger( $this->logger );
            $this->adapters[$type] = TypeAdapter::newDefaultInstance( $adapter );
        }
        if($type === "bool" && !isset($this->adapters[$type])){
            $adapter = new BoolAdapter();
            if(isset($this->logger) && is_a($adapter,LoggerAwareInterface::class))
                $adapter->setLogger( $this->logger );
            $this->adapters[$type] = TypeAdapter::newDefaultInstance( $adapter );
        }

        return (isset($this->adapters[$type])) ? $this->adapters[$type] : null;
    }

    /**
     * Регистрирует новый адаптер
     * @param object $adapter
     * @return $this
     */
    public function registeredAdapter( object $adapter ):self
    {
        $class = new ReflectionClass( $adapter );
        $attribute = $this->namespaceFilter->filtered($class->getAttributes(JsonAdapter::class ));

        /**@var JsonAdapter|null $attribute*/
        if(isset( $attribute )){

            if(is_a($adapter,LoggerAwareInterface::class))
                $adapter->setLogger( $this->logger );

            $adapter = new TypeAdapter( $adapter ,$this ,$attribute, $class );
            if( $adapter->isAdapter() ){
                $this->adapters[ $adapter->getTarget() ] = $adapter;
            }
        }else{
            $this->logger->warning(JsonDebug::NOT_ADAPTERS,[
                "class"=>$adapter::class,
                "attribute"=> JsonAdapter::class
            ]);
        }
        return $this;
    }



    public function toJson( object|array $data ):string|object|array|null
    {
        if(is_object($data)){
            $target = $data::class;
            if(!isset($this->matrix[$target]))$this->matrix[$target] = new Matrix( $target, $this );
            $matrix = $this->matrix[$target];
            $data =  $this->toJsonProcess($data,$matrix);
        }else{
            foreach ($data as $key => $value){
                if(is_object($value)){
                    $target = $value::class;
                    if(!isset($this->matrix[$target]))$this->matrix[$target] = new Matrix( $target, $this );
                    $matrix = $this->matrix[$target];
                }
                $data[$key] = $this->toJson($value);
            }
            if(isset($matrix)){
                $name = $matrix->getStartJsonKey();
                if(isset($name)) return [ $name => $data ];
            }
        }
        return $data;
    }

    /***
     * @param object $data
     * @param Matrix $matrix
     * @return array|null
     */
    public function toJsonProcess( object $data , Matrix $matrix):array|null
    {
        return $matrix->toJson($data);
    }

    /**
     * Возвращает логгер
     * @return LoggerInterface|null
     */
    public function getLogger():?LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Возвращает анализатор привязанный к
     * пространству имен
     * @param string $namespace
     * @return static
     */
    public static function get( string $namespace = Json::DEFAULT_NAMESPACE ):self
    {
        if(!isset(self::$instances[$namespace])) self::$instances[$namespace] = new self( $namespace );
        return self::$instances[$namespace];
    }

}