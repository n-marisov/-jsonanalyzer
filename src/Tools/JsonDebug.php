<?php

namespace Maris\JsonAnalyzer\Tools;

class JsonDebug
{
    const STRING_UNSUITABLE_JSON_DECODE = "Строка \"{json}\" не пригодна для json_decode";
    const TARGET_NOT_CLASS_EXISTS = "Тип данных \"{class}\" не является php объектом.";
    const NOT_ARRAY_OR_OBJECT = "Данные в строке \"{json}\" не является json объектом или json массивом.";
    const SEARCH_KEY_NOT_OBJECT = "При анализе ключа \"{key}\" передан не объект!";
    const OBJECT_NOT_KEY_EXIST = "Ключа \"{key}\" не существует в объекте c данными";

    const NOT_DATA_OBJECT_IN_KEY = "В объекте под ключом \"{key}\" нет данных для создания объекта !";

    const OBJECT_NOT_TO_STRING = "Объект класса \"{class}\" невозможно привести к строке";

    const NOT_METHOD_ADAPTER = "В адаптере \"{class}\" не зарегистрирован метод \"{method}\", в пространстве имен \"{namespace}\". Адаптер не зарегистрирован ! ";

    const TARGET_INVALID_ADAPTER = "Невозможно зарегистрировать адаптер для типа данных \"{target}\" в пространстве имен \"{namespace}\" !";

    const UNIQUE_CONFLICT_PARENT = "В классе \"{class}\", в пространстве имен \"{namespace}\", есть свойства которые помечены атрибутом \"{attribute}\" уникальность не задействована на данном классе .";

    const PROPERTY_INVALID_TYPE = "В классе \"{class}\", объявленный тип данных свойства \"{property}\" не позволяет записать тип \"{type}\" в пространстве имен \"{namespace}\" .";

    const PROPERTY_TYPE_AMBIGUITY = "Найдена неоднозначность в классе \"{class}\" , свойстве \"{property}\" скорее всего не указан параметр \"{parameter}\" в пространстве имен \"{namespace}\"";

    const INVALID_RETURNED_TYPE = "Тип возвращаемого значения не может быть \"void\" или \"never\" . Ошибка возникла в классе \"{class}\" в методе \"{method}\". В пространстве имен \"{namespace}\"";

    const PROPERTY_NOT_INITIALIZED = "Свойство \"{property}\" не инициализировано. В классе \"{class}\".В пространстве имен \"{namespace}\".";

    const ERROR_CREATE_MATRIX = "Ошибка при создании матрицы объекта класса \"{class}\". В пространстве имен \"{namespace}\"";

    const ERROR_CREATE_PROPERTY_MATRIX = "Ошибка создания матрицы класса \"{class}\", в свойстве\"{property}\".В пространстве имен \"{namespace}\".";

    const ERROR_CREATE_METHOD_MATRIX = "Ошибка создания матрицы класса \"{class}\", в методе \"{method}\".В пространстве имен \"{namespace}\".";

    const ERROR_CREATE_PARAMETER_MATRIX = "Ошибка создания матрицы класса \"{class}\", в методе \"{method}\" в параметре \"{parameter}\".В пространстве имен \"{namespace}\".";

    const RUNTIME_ADAPTER_EXCEPTION = "Ошибка адаптера \"{target}\".В методе \"{method}\". В пространстве имен \"{namespace}\"";

    const NOT_ADAPTERS = "Объект класса \"{class}\" не помечен атрибутом \"{attribute}\" и не является адаптером !";

    const ERROR_NEW_INSTANCE_WITHOUT_CONSTRUCTOR = "Ошибка создания экземпляра класса \"{class}\".В пространстве имен \"{namespace}\".";

    const ERROR_INVOKE_METHOD = "Ошибка вызова метода {method}. В классе \"{class}\".В пространстве имен \"{namespace}\".";
}