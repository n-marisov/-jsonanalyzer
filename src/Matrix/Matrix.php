<?php

namespace Maris\JsonAnalyzer\Matrix;
use Maris\JsonAnalyzer\Attributes\FromJson;
use Maris\JsonAnalyzer\Attributes\JsonIgnore;
use Maris\JsonAnalyzer\Attributes\JsonObject;
use Maris\JsonAnalyzer\Attributes\JsonParent;
use Maris\JsonAnalyzer\Attributes\ToJson;
use Maris\JsonAnalyzer\Tools\CleanerEmpty;
use Maris\JsonAnalyzer\Tools\JsonDebug;
use Maris\JsonAnalyzer\Tools\ObjectAnalyzer;
use Maris\JsonAnalyzer\Tools\MethodFilter;
use Maris\JsonAnalyzer\Tools\UniqueFilter;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use stdClass;

/**
 * Класс представляет собой матрицу,
 * отпечатанную с класса php
 * @template T
 */
class Matrix extends ReflectionClass
{
    /**
     * Родительский анализатор
     * @var ObjectAnalyzer
     */
    public ObjectAnalyzer $analyzer;
    /**
     * Атрибут
     * @var JsonIgnore|null
     */
    protected ?JsonIgnore $ignore;

    /**
     * Атрибут
     * @var JsonObject|null
     */
    protected ?JsonObject $jsonObject;


    /**
     * Метод класса который помечен атрибутом FromJson
     * @var ReflectionMethod|null
     */
    protected ?ReflectionMethod $fromJsonMethod;

    /**
     * Метод класса который помечен атрибутом ToJson
     * @var ReflectionMethod|null
     */
    protected ?ReflectionMethod $toJsonMethod;

    /**
     * @var array<Property>
     */
    protected array $properties = [];

    /**
     * @var array<Method>
     */
    protected array $methods = [];

    /**
     * @var array<Constant>
     */
    protected array $constants = [];

    /**
     * @param class-string $objectOrClass
     * @param ObjectAnalyzer $analyzer
     */
    public function __construct( string $objectOrClass , ObjectAnalyzer $analyzer )
    {
        $this->analyzer = $analyzer;

        try {
            parent::__construct($objectOrClass);
        }catch ( ReflectionException $exception){
            $analyzer->getLogger()->error(JsonDebug::ERROR_CREATE_MATRIX,[
                "class" => $objectOrClass,
                "namespace"=>$analyzer->namespace,
                "exception"=>[
                    "message" => $exception->getMessage(),
                    "code" => $exception->getCode(),
                    "trace" => $exception->getTrace()
                ]
            ]);
        }

        $this->ignore = $analyzer->namespaceFilter->filtered( $this->getAttributes(JsonIgnore::class) );
        $this->jsonObject = $analyzer->namespaceFilter->filtered( $this->getAttributes(JsonObject::class) );


        $methodFilter = new MethodFilter($analyzer->namespaceFilter);

        $this->fromJsonMethod =  $methodFilter->search( $this, FromJson::class );
        $this->toJsonMethod =  $methodFilter->search( $this, ToJson::class );


        foreach ( $this->getProperties() as $property){
            $this->properties[] = new Property( $property, $analyzer );
        }
        foreach ( $this->getMethods() as $method){
            $this->methods[] = new Method( $method, $analyzer );
        }
        foreach ( $this->getReflectionConstants() as $constant){
            $this->constants[] = new Constant( $constant, $analyzer );
        }

    }

    public function getStartJsonKey():?string
    {
        return $this->jsonObject?->name ?? null;
    }

    /**
     * @param stdClass $data
     * @param object|null $parent
     * @return T
     */
    public function fromJson( stdClass $data , ?object $parent = null):object
    {

        $instance = $this->newInstanceWithoutConstructor();
        # Если определен метод fromJson передаем ему
        if( isset($this->fromJsonMethod) ){

            $this->fromJsonMethod->invoke( $instance, (array)$data , $parent);
            return $instance;
        }

        foreach ( $this->properties as $property){
            if(!$property->isIgnore( from: false ) && ($property->isJsonProperty() || $property->isJsonParent()) ){
                # Свойство помечено атрибутом и допускается игнорированием
                $property->setValue( $instance, $data, $parent );
            }
        }

        foreach ( $this->methods as $method ){
            # Обрабатываем только помеченные методы
            if(!$method->isIgnore( from: false ) && $method->isSetter() ){
                # Метод помечен атрибутом и допускается игнорированием
                $method->invokeSetter( $instance, $data, $parent );
            }
        }

        # Проверяем на уникальность
        if($this->jsonObject?->unique){

            if($this->existParentProperty()){
                $this->analyzer->getLogger()?->warning(JsonDebug::UNIQUE_CONFLICT_PARENT,[
                        "class"=>$this->getName(),
                        "attribute"=>JsonParent::class,
                        "namespace"=>$this->analyzer->namespace
                ]);
                return $instance;
            }

            if( ($filter =  UniqueFilter::get($instance)) !== false ){
                $instance = $filter->getResult();
            }else UniqueFilter::add($instance,$instance);
        }

        # Иначе наполняем объект сами
        return $instance;
    }

    /**
     * Указывает на наличие свойств помеченных атрибутом JsonParent
     * @return bool
     */
    private function existParentProperty():bool
    {
        foreach ($this->properties as $property)
            if($property->isJsonParent()) return true;
        return false;
    }
    /**
     * @param object<T> $instance
     * @return array
     */
    public function toJson( object $instance ):array
    {
        # Если определен метод toJson
        if( isset($this->toJsonMethod) ){
            $type = new Type( $this->toJsonMethod->getReturnType() );
            if($type->isNever() || $type->isVoid()){
                $this->analyzer->getLogger()?->warning(JsonDebug::INVALID_RETURNED_TYPE,[
                    "class"=>$this->name,
                    "method"=>$this->toJsonMethod->getName(),
                    "namespace"=>$this->analyzer->namespace
                ]);
                return [];
            } else return $this->toJsonMethod->invoke( $instance );
        }

        $data = [];

        foreach ( $this->constants as $constant)
            if( !$constant->isIgnore( to: false ) && $constant->isGetter()){
                # Свойство помечено атрибутом и допускается игнорированием
                $data[$constant->getJsonName()] = $constant->getValue();
            }
        foreach ( $this->properties as $property)
            if(!$property->isIgnore( from: false ) && $property->isJsonProperty() && !$property->isJsonParent() && $property->isInitialized($instance)){
                # Свойство помечено атрибутом и допускается игнорированием
                $this->toJsonDataMerge($data, $property->getJsonName(), $property->getValue( $instance ) );
            }
        foreach ( $this->methods as $method )
            # Свойство помечено атрибутом и допускается игнорированием
            if( !$method->isIgnore( to: false ) && $method->isGetter() ){
                $this->toJsonDataMerge($data, $method->getJsonName(), $method->invoke( $instance ) );
            }


        # Чистим от пустых
        if($this->jsonObject?->removeEmpty){
            CleanerEmpty::clear($data);
        }
        return $data;
    }

    /**
     * Обновляют данные при приведении к json
     * @param array $data
     * @param string|null $key
     * @param mixed $value
     * @return void
     */
    private function toJsonDataMerge(array &$data , ?string $key, mixed $value):void
    {
        # Если ключ отсутствует объединяем с текущим массивом
        if(!isset($key) && is_array($value) ) $data = array_merge( $data, $value );
        elseif( isset($key) ){
            # Если данные в указанном ключе не существуют
            if(!isset($data[$key])){
                $data[$key] = $value;
            }
            # Если в данном ключе массив и текущие данные массив (и не лист) объединяем
            elseif( is_array($data[$key]) && is_array($value) && !array_is_list($value)){
                $data[$key] = array_merge($data[$key],$value);
            }
        }
    }

}