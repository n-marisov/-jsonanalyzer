<?php

namespace Maris\JsonAnalyzer\Matrix;

use JetBrains\PhpStorm\Pure;
use ReflectionIntersectionType as IntersectionType;
use ReflectionNamedType as NamedType;
use ReflectionType;
use ReflectionUnionType as UnionType;

class Type extends ReflectionType
{
    public readonly ?ReflectionType $type;

    /**
     * @param ReflectionType|null $type
     */
    public function __construct( ?ReflectionType $type  )
    {
        $this->type = $type;
    }

    /**
     * Определяет что тип однозначно один
     * @return bool
     */
    public function isUniquely():bool
    {
        return !is_a( $this->type,UnionType::class );
    }


    /**
     * Получает имя типа если оно однозначно
     * @return string|null
     */
    public function getTypeName():?string
    {
        if(is_a($this->type,NamedType::class)) return $this->type->getName();
        return null;
    }

    /**
     * Проверяет, является ли тип встроенным
     * @return bool
     */
    #[Pure] public function isBuiltin():bool
    {
        # Тип объединение не может быть встроенным
        if( is_a($this->type, IntersectionType::class ) ) return false;
        # Тип один, проверяем является ли он встроенным
        if( is_a($this->type, NamedType::class ) )  return $this->type->isBuiltin();
        if( is_a($this->type, UnionType::class ) )  foreach ($this->type->getTypes() as $type){
            # Если хотя бы один тип не является встроенным, то тип не признается встроенным
            if( !$type->isBuiltin()) return false;
        }
        # Если тип не объявлен он может принимать любой встроенный тип
        return true;
    }

    /**
     * Определяет наличие скалярных (Встроенных типов)
     * @return bool
     */
    public function issetBuiltin():bool
    {
        # Тип объединение не может быть встроенным
        if( is_a($this->type, IntersectionType::class ) ) return false;
        # Тип один, проверяем является ли он встроенным
        if( is_a($this->type, NamedType::class ) )  return $this->type->isBuiltin();
        if( is_a($this->type, UnionType::class ) )  foreach ($this->type->getTypes() as $type){
            # Если хотя бы один тип является встроенным, то тип имеет в своем составе встроенные типы
            if( $type->isBuiltin() ) return true;
        }
        # Если тип не объявлен он может принимать любой встроенный тип
        return false;
    }

    /**
     * Возвращает массив со всеми встроенными типами
     * @return array<string>
     */
    public function getBuiltins():array
    {
        $result = [];
        # Тип один, проверяем является ли он встроенным
        if( is_a($this->type, NamedType::class ) && $this->type->isBuiltin())  $result[] = $this->type->getName();
        if( is_a($this->type, UnionType::class ) )  foreach ($this->type->getTypes() as $type){
            # Если хотя бы один тип не является встроенным, то тип не признается встроенным
            if(is_a($type, NamedType::class ) && $type->isBuiltin()) $result[] = $type->getName();
        }
        return $result;
    }


    /**
     * Определяет, является ли тип обнуляемым
     * @return bool
     */
    public function allowsNull(): bool
    {
        # Тип объединение не может быть, обнуляемым
        if( is_a($this->type, IntersectionType::class ) ) return false;
        if( is_a($this->type, NamedType::class ) ) return $this->type->allowsNull();
        if( is_a($this->type, UnionType::class ))  foreach ($this->type->getTypes() as $type){
            # Если хотя бы один тип может принять null тип считается обнуляемым
            if( is_a($type, NamedType::class ) && $type->allowsNull() ) return true;
        }
        return false;
    }

    /**
     * Определяет, может ли тип принять данное значение
     * @param mixed $value
     * @return bool
     */
    public function allowsValue( mixed $value ):bool
    {
        # Если значение null проверяем на обнуляемость
        if(is_null($value)) return $this->allowsNull();

        if( is_a($this->type, NamedType::class ) ){
            return self::allowsValueNamedType( $this->type, $value );
        }

        if( is_a($this->type, UnionType::class ) ){
            return self::allowsValueUnionType( $this->type, $value );
        }

        if( is_a($this->type, IntersectionType::class ) ) {
            return self::allowsValueIntersectionType( $this->type, $value );
        }
        # Значит свойство не типизировано можно присвоить любое значение
        return true;
    }

    /**
     * Определяет доступно ли значение для записи
     * в данный UnionType тип.
     * Значение считается доступным, если хотя бы
     * для одного встроенного типа оно считается доступно
     *
     * @param UnionType $types
     * @param mixed $value
     * @return bool
     */
    protected static function allowsValueUnionType( UnionType $types , mixed $value ):bool
    {
        # Если хотя бы один тип не удовлетворяет значит значение не является этим типом
        foreach ($types->getTypes() as $type)
            if (is_a($type,NamedType::class) && self::allowsValueNamedType($type,$value)) return true;
            elseif (is_a($type,UnionType::class) && self::allowsValueUnionType($type,$value)) return true;
        return false;
    }


    /**
     * Определяет доступно ли значение для записи
     * в данный IntersectionType тип
     * Значение считается доступным если является объектом
     * и наследует все типы объединения
     * @param IntersectionType $types
     * @param mixed $value
     * @return bool
     */
    protected static function allowsValueIntersectionType( IntersectionType $types , mixed $value ):bool
    {
        if(!is_object($value)) return false;
        # Если хотя бы один тип не удовлетворяет значит значение не является этим типом
        foreach ($types->getTypes() as $type)
            if (is_a($type,NamedType::class) && !self::allowsValueNamedType($type,$value)) return false;
            elseif (is_a($type,UnionType::class) && !self::allowsValueUnionType($type,$value)) return false;
        return true;
    }

    /**
     * Определяет доступно ли значение для записи в данный NamedType тип.
     * Значение считается доступно, если оно является скалярным
     * и название типа соответствует типу значения или
     * значение объект и является NamedType или наследует его.
     * @param NamedType $type
     * @param mixed $value
     * @return bool
     */
    protected static function allowsValueNamedType( NamedType $type , mixed $value ):bool
    {
        if($type->isBuiltin()){
            return match ( $type->getName() ){
                # Если тип является номерным (string|int|float) или логическим
                "float","int" => is_numeric($value) || is_bool($value),
                # Если скаляр или объект у которого реализован метод __toString()
                "string" => is_scalar( $value ) || (is_object($value) && method_exists($value,"__toString")),
                # К логическому значению можно привести любой тип
                "bool" => true,
                # Только если массив
                "array" => is_array( $value ),
                default => false
            };
            # Если тип не встроенный, то объект.
        }else return is_object($value) && is_a( $value, $type->getName() );
    }

    /**
     * Указывает на то что тип данных void
     * @return bool
     */
    public function isVoid():bool
    {
        return is_a($this->type,NamedType::class) && $this->type->getName() === "void";
    }

    /**
     * Указывает на то что тип данных never
     * @return bool
     */
    public function isNever():bool
    {
        return is_a($this->type,NamedType::class) && $this->type->getName() === "never";
    }
}