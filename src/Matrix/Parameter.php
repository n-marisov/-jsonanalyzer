<?php

namespace Maris\JsonAnalyzer\Matrix;

use App\Tests\Pojo\Address;
use App\Tests\Pojo\Component\Metro;
use Maris\JsonAnalyzer\Tools\ObjectAnalyzer;
use ReflectionException;
use ReflectionParameter;

class Parameter extends ReflectionParameter
{

    public readonly ObjectAnalyzer $analyzer;
    public readonly Type $type;

    public function __construct( ReflectionParameter $parameter, ObjectAnalyzer $analyzer )
    {
        $this->analyzer = $analyzer;
        parent::__construct( [
            $parameter->getDeclaringClass()->getName(),
            $parameter->getDeclaringFunction()->getName()
        ],
            $parameter->getName() );
        $this->type = new Type( $this->getType() );
    }

    /**
     * Определяет допустипо ли значение
     * для передачи в качестве параметра
     * @param mixed $value
     * @return bool
     */
    public function isAvailable( mixed $value ): bool
    {
        return $this->type->allowsValue( $value );
    }

    /**
     * Формирует значение на основе
     * Данных переданых в класс
     * @param mixed $data если массив создаеем массив $target[]
     * @param string|null $target если пустой формируем на основании типа данных
     * @return mixed
     * @throws ReflectionException
     */
    public function createValue( mixed $data , ?string $target = null , ?object $parent =  null):mixed
    {
        # если массив создаеем массив $target[]
        if( is_array($data) ){
            foreach ($data as $key => $value){
                $data[$key] = $this->createValue( $data, $target, $parent );
            }
            return $data;
        }

        # Если не пустой создаем и возврощаем сущность типа $target
        if( isset($target) ){
            # Ищем адаптер
            $adapter = $this->analyzer->getTypeAdapter($target);
            # Если есть адаптер передаем ему
            if(isset($adapter)){
                return $adapter->fromJson($data);
            }
            # Если класс существует создаем новую сущность
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

        # Пытаемся вычислить $target и запускаем рекурсию
        # Значит определен один тип
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