<?php

namespace Maris\JsonAnalyzer\Matrix;

use Maris\JsonAnalyzer\Analyzer;
use Maris\JsonAnalyzer\Attributes\JsonIgnore;
use Maris\JsonAnalyzer\Attributes\JsonParent;
use Maris\JsonAnalyzer\Attributes\JsonProperty;
use Maris\JsonAnalyzer\Exceptions\NotFoundException;
use Maris\JsonAnalyzer\Tools\JsonDebug;
use ReflectionException;
use ReflectionProperty;

class Property extends ReflectionProperty
{
    /**
     * Родительский анализатор
     * @var Analyzer
     */
    public readonly Analyzer $analyzer;
    /**
     * Атрибут
     * @var JsonIgnore|null
     */
    protected readonly ?JsonIgnore $ignore;

    /**
     * Атрибут
     * @var JsonParent|null
     */
    protected readonly ?JsonParent $jsonParent;
    /**
     * Атрибут
     * @var JsonProperty|null
     */
    protected readonly ?JsonProperty $jsonProperty;

    /**
     * Тип данных
     * @var Type
     */
    protected readonly Type $type;

    /**
     * @param ReflectionProperty $property
     * @param Analyzer $analyzer
     */
    public function __construct( ReflectionProperty $property , Analyzer $analyzer )
    {
        $this->analyzer = $analyzer;
        $this->ignore = $analyzer->namespaceFilter->filtered($property->getAttributes(JsonIgnore::class));
        $this->jsonParent = $analyzer->namespaceFilter->filtered( $property->getAttributes(JsonParent::class) );
        $this->jsonProperty = $analyzer->namespaceFilter->filtered($property->getAttributes(JsonProperty::class));

        try {
            parent::__construct( $property->class, $property->name );
        }catch (ReflectionException $exception){
            $analyzer->getLogger()->error(JsonDebug::ERROR_CREATE_PROPERTY_MATRIX,[
                "class" => $property->class,
                "property"=>$property->name,
                "namespace"=>$analyzer->namespace,
                "exception"=>[
                    "message" => $exception->getMessage(),
                    "code" => $exception->getCode(),
                    "trace" => $exception->getTrace()
                ]
            ]);
        }



        $this->type = new Type( $this->getType() );

    }

    /**
     * Указывает на то что свойство помечено атрибутом
     * #[JsonProperty]
     * @return bool
     */
    public function isJsonProperty():bool
    {
        return isset($this->jsonProperty);
    }

    public function isJsonParent():bool
    {
        return isset($this->jsonParent);
    }
    /**
     * Указывает на то что свойство помечено атрибутом
     * #[JsonIgnore]
     * @param bool|null $from
     * @param bool|null $to
     * @return bool
     */
    public function isIgnore( bool $from = true, bool $to = true ):bool
    {
        # Значит атрибутом не помечен
        if(!isset($this->ignore)) return false;
        return $from !== $this->ignore->fromJson || $to !== $this->ignore->toJson;
    }

    /**
     * Устанавливает свойство
     * @param mixed $objectOrValue
     * @param mixed|null $value
     * @param object|null $parent
     * @return void
     */
    public function setValue( mixed $objectOrValue, mixed $value = null , ?object $parent = null ): void
    {
        # Если помечена родителем присваиваем его
        if( isset($this->jsonParent) ){

            if(!$this->type->allowsValue($parent))
                $this->analyzer->getLogger()?->debug(JsonDebug::PROPERTY_INVALID_TYPE,[
                    "class"=>$this->class,
                    "property" => $this->name,
                    "type"=> (is_null($parent)) ? "null" : $parent::class,
                    "namespace"=> $this->analyzer->namespace
                ]);
            else parent::setValue($objectOrValue, $parent);
            return;
        }

        # Получаем свойство если есть свойство name
        if(isset($this->jsonProperty->name)){
            if( property_exists( $value, $this->jsonProperty->name ))
                $value = $value->{ $this->jsonProperty->name };
            else $this->analyzer->getLogger()?->debug(JsonDebug::OBJECT_NOT_KEY_EXIST,[
                "key" => $this->jsonProperty->name,
                "data"=> $value
            ]);
        }

        # Если указан $target ищем адаптер
        $target = $this->jsonProperty->target ?? $this->type->getTypeName();
        //$adapter = $this->analyzer->getTypeAdapter( $target );
        # Если есть адаптер передаем ему
        /*if(isset($adapter)){
            if(is_object($value)) $value = (array)$value;
            $value = $adapter->fromJson( $value );
        }*/


        if($this->analyzer->adapters->has($target)){
            try {
                $adapter = $this->analyzer->adapters->get($target);
                if(is_object($value)) $value = (array)$value;
                $value = $adapter->fromJson( $value ,$this->analyzer->namespace);
            } catch (NotFoundException $e) {
                $this->analyzer->getLogger()->error( $e->getMessage() );
            }
        }


        # Если тип один
        if( $this->type->isUniquely() ){
            if(is_object($value)){
                $target = $this->jsonProperty->target ?? $this->type->getTypeName();
                $value = $this->analyzer->fromArray( $value, $target , $objectOrValue );
            }
        }elseif ( !isset($target) ) $this->analyzer->getLogger()?->debug(JsonDebug::PROPERTY_TYPE_AMBIGUITY,[
            "class"=>$this->class,
            "property" => $this->name,
            "parameter"=>"\$target",
            "namespace"=> $this->analyzer->namespace,
        ]);



        # Если свойство допустимо устанавливаем
        if(!$this->type->allowsValue($value))
            $this->analyzer->getLogger()?->debug(JsonDebug::PROPERTY_INVALID_TYPE,[
                "class"=>$this->class,
                "property" => $this->name,
                "type"=> (is_object($value)) ? $value::class : gettype($value),
                "namespace"=> $this->analyzer->namespace,
                "data"=>$value
            ]);
        else parent::setValue( $objectOrValue, $value );
    }

    /**
     * Возвращает значение свойства
     * @param object|null $object
     * @return mixed
     */
    public function getValue( ?object $object = null ): mixed
    {
        if(!$this->isInitialized($object)){
            $this->analyzer->getLogger()?->notice(JsonDebug::PROPERTY_NOT_INITIALIZED,[
                "class"=>$this->class,
                "property" =>$this->name,
                "namespace"=>$this->analyzer->namespace
            ]);
            return null;
        }
        $value = parent::getValue( $object );
        if(isset($this->jsonProperty?->target)){
            if($this->analyzer->adapters->has($this->jsonProperty?->target))
                try {
                    return $this->analyzer->adapters
                        ->get($this->jsonProperty?->target)
                        ?->toJson($value, $this->analyzer->namespace);
                } catch (NotFoundException $e) {
                    $this->analyzer->getLogger()?->error($e);
                }
        }

        if(is_object($value)) return $this->analyzer->toArray($value);
        if(is_array($value)) foreach ($value as $k => $v ){
            $value[$k] = $this->analyzer->toArray($v);
        }
        return $value;
    }

    /**
     * Возвращает название ключа в массиве json
     * @return string|null
     */
    public function getJsonName():?string
    {
        return $this->jsonProperty->name;
    }

}