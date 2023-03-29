<?php

namespace Maris\JsonAnalyzer\Matrix;


use Maris\JsonAnalyzer\Attributes\JsonGetter;
use Maris\JsonAnalyzer\Attributes\JsonIgnore;
use Maris\JsonAnalyzer\Attributes\JsonParent;
use Maris\JsonAnalyzer\Attributes\JsonSetter;
use Maris\JsonAnalyzer\Tools\JsonDebug;
use Maris\JsonAnalyzer\Tools\ObjectAnalyzer;

use ReflectionException;
use ReflectionMethod;

class Method extends ReflectionMethod
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
     * @var JsonParent|null
     */
    protected ?JsonParent $jsonParent;

    /**
     * Атрибут
     * @var JsonGetter|null
     */
    protected ?JsonGetter $jsonGetter;

    /**
     * Атрибут
     * @var JsonSetter|null
     */
    protected ?JsonSetter $jsonSetter;

    /**
     * @var array<Parameter>
     */
    protected array $parameters = [];

    /**
     * Тип возвращаемого значения
     * @var Type
     */
    protected Type $returned;

    /**
     * @param ReflectionMethod $method
     * @param ObjectAnalyzer $analyzer
     */
    public function __construct( ReflectionMethod $method , ObjectAnalyzer $analyzer )
    {
        $this->analyzer = $analyzer;
        $this->ignore = $analyzer->namespaceFilter->filtered($method->getAttributes(JsonIgnore::class));
        $this->jsonParent = $analyzer->namespaceFilter->filtered($method->getAttributes(JsonParent::class));
        $this->jsonGetter = $analyzer->namespaceFilter->filtered($method->getAttributes(JsonGetter::class));
        $this->jsonSetter = $analyzer->namespaceFilter->filtered($method->getAttributes(JsonSetter::class));

        try {
            parent::__construct( $method->class, $method->name );
        }catch (ReflectionException $exception){
            $analyzer->getLogger()->error(JsonDebug::ERROR_CREATE_METHOD_MATRIX,[
                "class" => $method->class,
                "method"=>$method->name,
                "namespace"=>$analyzer->namespace,
                "exception"=>[
                    "message" => $exception->getMessage(),
                    "code" => $exception->getCode(),
                    "trace" => $exception->getTrace()
                ]
            ]);
        }

        foreach ($this->getParameters() as $parameter)
            $this->parameters[] = new Parameter( $parameter, $analyzer );

        $this->returned = new Type( $this->getReturnType() );
    }


    /**
     * Указывает на наличие гетера
     * @return bool
     */
    public function isGetter():bool
    {
        return isset($this->jsonGetter);
    }

    /**
     * Указывает на наличие гетера
     * @return bool
     */
    public function isSetter():bool
    {
        return isset( $this->jsonSetter );
    }

    /**
     * Указывает на то что метод должен принимать родителя
     * @return bool
     */
    public function isParent():bool
    {
        return isset($this->jsonParent);
    }
    /**
     * Определяет нужно ли печатать объект
     * @param bool|null $from
     * @param bool|null $to
     * @return bool
     */
    public function isIgnore( ?bool $from = null, ?bool $to = null):bool
    {
        # Значит атрибутом не помечен
        if(!isset($this->ignore)) return false;
        return $from !== $this->ignore->fromJson || $to !== $this->ignore->toJson;
    }


    /**
     * @throws ReflectionException
     */
    public function invokeSetter(mixed $objectOrValue, mixed $value = null , ?object $parent = null ):void
    {
        $arguments = [];
        $isValidArguments = true;

        # Если указан ключ переходим на него
        if(isset( $this->jsonSetter->name )){
            if (!is_object($value)){
                $this->analyzer->getLogger()?->debug(JsonDebug::SEARCH_KEY_NOT_OBJECT,[
                    "key"=>$this->jsonSetter->name,
                    "data"=>$value
                ]);
                return;
            }elseif (!property_exists($value,$this->jsonSetter->name)){
                $this->analyzer->getLogger()?->debug(JsonDebug::OBJECT_NOT_KEY_EXIST,[
                    "key"=>$this->jsonSetter->name,
                    "data"=>$value
                ]);
                return;
            }else $value = $value->{$this->jsonSetter->name};
        }

        foreach ( $this->parameters as $position => $parameter ){
            # Если параметр последний и помечено #[JsonParent] и родитель доступен для записи
            if( $this->isParent() && (count($this->parameters)-1) === $position ){
                if(!$parameter->type->allowsValue($parent)){
                    $this->analyzer->getLogger()?->debug(JsonDebug::PROPERTY_INVALID_TYPE,[
                        "class"=>$this->class,
                        "property"=>$this->name,
                        "type"=>(is_null($parent)) ? "null" : $parent::class,
                        "namespace"=>$this->analyzer->namespace
                    ]);
                }else {
                    $arguments[] = $parent;
                    break;
                }
            }

            $target = $this->jsonSetter->target;

            if(is_array($target)){
                $target = match (true){
                    isset($target[$parameter->name]) => $target[$parameter->name],
                    isset($target[$position]) => $target[$position],
                    default => null
                };
            }

            $data = $parameter->createValue( $value, $target , $parent);

            # Если тип переменной подходит для установки в параметр
            if( $parameter->type->allowsValue($data) ){
                $arguments[] = $data;
            }

            # Если есть значение по умолчанию
            elseif ($parameter->isDefaultValueAvailable())
                $arguments[] = $parameter->getDefaultValue();

            # Если не чего не подходит отменяем вызов сетера
            else $isValidArguments = false;
        }
        if($isValidArguments && count($arguments) > 0){
            $this->invokeArgs( $objectOrValue, $arguments );
        }
    }

    public function getJsonName():?string
    {
        return $this->jsonGetter->name;
    }

}