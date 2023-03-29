<?php

namespace Maris\JsonAnalyzer\Matrix;

use Maris\JsonAnalyzer\Tools\JsonDebug;
use Maris\JsonAnalyzer\Tools\ObjectAnalyzer;
use ReflectionException;
use ReflectionParameter;

class Parameter extends ReflectionParameter
{

    public readonly ObjectAnalyzer $analyzer;
    public readonly Type $type;

    /**
     * @param ReflectionParameter $parameter
     * @param ObjectAnalyzer $analyzer
     */
    public function __construct( ReflectionParameter $parameter, ObjectAnalyzer $analyzer )
    {
        $this->analyzer = $analyzer;

        try {
            parent::__construct( [
                $parameter->getDeclaringClass()->getName(),
                $parameter->getDeclaringFunction()->getName()
            ],
                $parameter->getName() );
        }catch (ReflectionException $exception){
            $analyzer->getLogger()->error(JsonDebug::ERROR_CREATE_PARAMETER_MATRIX,[
                "class" => $parameter->getDeclaringClass()->getName(),
                "method"=>$parameter->getDeclaringFunction()->getName(),
                "parameter" => $parameter->getName(),
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
     * Формирует значение на основе
     * Данных переданных в класс
     * @param mixed $data Если массив создаем массив $target[]
     * @param string|null $target Если пустой формируем на основании типа данных
     * @param object|null $parent
     * @return mixed
     */
    public function createValue( mixed $data , ?string $target = null , ?object $parent =  null):mixed
    {
        # если массив, создаем массив $target[]
        if( is_array($data) ){
            foreach ($data as $key => $value){
                $data[$key] = $this->createValue( $data, $target, $parent );
            }
            return $data;
        }

        # Если не пустой создаем и возвращаем сущность типа $target
        if( isset($target) ){
            # Ищем адаптер
            $adapter = $this->analyzer->getTypeAdapter($target);
            # Если есть адаптер передаем ему
            if(isset($adapter)){
                return $adapter->fromJson($data);
            }
            # Если класс существует, создаем новую сущность
            elseif (class_exists($target)){
                return $this->analyzer->fromJson($data,$target,$parent);
            }
            elseif ($target === "array"){
                return (array)$data;
            }

            elseif ($target === "object"){
                return (object)$data;
            }
        }

        # Пытаемся вычислить $target и запускаем рекурсию.
        # Значит определен один тип.
        if($this->type->isUniquely()){
            $target = $this->type->getTypeName();
            if(isset($target)) return $this->createValue( $data, $target ,$parent);
        }

        # Если типов несколько пытаемся получить первый скалярный
        if($this->type->issetBuiltin()){
            $types = $this->type->getBuiltins();
            if(isset($types[0])) return match ( $types[0] ){
                "string"=>$this->createValue( $data, "string" ,$parent),
                "array" =>$this->createValue( $data, "array" ,$parent),
                default => null
            };
        }

        return null;
    }

}