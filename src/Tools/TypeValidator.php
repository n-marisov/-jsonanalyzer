<?php

namespace Maris\JsonAnalyzer\Tools;

use ReflectionIntersectionType as IntersectionType;
use ReflectionNamedType as NamedType;
use ReflectionUnionType as UnionType;

class TypeValidator
{
    public UnionType|IntersectionType|NamedType|null $type;
    public function __construct( UnionType|IntersectionType|NamedType|null $type )
    {
        $this->type = $type;
    }

    /**
     * Указывает на то что свойство не типизировано
     * @return bool
     */
    public function untyped():bool
    {
       return $this->type == null;
    }
    /**
     * Указывает на то что типизировано и тип один
     * @return bool
     */
    public function uniquely():bool
    {
        return  $this->type::class == NamedType::class;
    }

    /**
     * Возврощает название типа
     * @return string
     */
    public function getTypeName():string
    {
        return $this->type->getName();
    }

    /**
     * Указывает на то что свойство доступно для записи
     * @param mixed $value
     * @return bool
     */
    public function allows( mixed $value ):bool
    {
        if( $this->untyped() ) return true;
        if( $this->type::class === NamedType::class){
            if(is_object($value) && $value::class === $this->type->getName()) return true;
            if($this->type->getName() === "array" && is_array($value)) return true;
            if(in_array($this->type->getName() ,["float","int"]) && is_numeric($value)) return true;
            if($this->type->getName() === "string" && is_string($value)) return true;
        }

        return false;
    }

}