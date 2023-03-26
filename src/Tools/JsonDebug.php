<?php

namespace Maris\JsonAnalyzer\Tools;

class JsonDebug
{
    const STRING_UNSUITABLE_JSON_DECODE = "Строка \"{json}\" не пригодна для json_decode";
    const TARGET_NOT_CLASS_EXISTS = "Тип данных \"{class}\" не является php обьектом.";
    const NOT_ARRAY_OR_OBJECT = "Данные в стороке \"{json}\" не является json обьектом или json массивом.";
    const SEARCH_KEY_NOT_OBJECT = "При анализе ключа \"{key}\" передан не обьект!";
    const OBJECT_NOT_KEY_EXIST = "Ключа \"{key}\" не существует в обьекте c данными";

    const NOT_DATA_OBJECT_IN_KEY = "В обьекте под ключем \"{key}\" нет данных для создания обьекта !";

    const OBJECT_NOT_TO_STRING = "Обьект класса \"{class}\" невозможно привести к строке";

    const NOT_METHOD_ADAPTER = "В адаптере \"{class}\" не зарегистрирован метод \"{method}\", в пространстве имен \"{namespace}\". Адаптер не зарегистрирован ! ";

    const TARGET_INVALID_ADAPTER = "Невозможно зарегистрировать адаптер для типа данных \"{target}\" в пространстве имен \"{namespace}\" !";

    const UNIQUE_CONFLICT_PARENT = "В класе \"{class}\", в пространстве имен \"{namespace}\", есть свойства которые помечены атрибутом \"{attribute}\" уникальность не задействована на данном класе .";

    const PROPERTY_INVALID_TYPE = "В классе \"{class}\", обьявленый тип данных свойства \"{property}\" не позволяет записать тип \"{type}\" в пространстве имен \"{namespace}\" .";

    const PROPERTY_TYPE_AMBIGUITY = "Найдена неоднозначность в классе \"{class}\" , свойстве \"{property}\" скорее всего не указан параметр \"{parameter}\" в пространстве имен \"{namespace}\"";

    const INVALID_RETURNED_TYPE = "Тип возврощаемого значения не может быть \"void\" или \"never\" . Ошибка возникла в классе \"{class}\" в методе \"{method}\". В пространстве имен \"{namespace}\"";

    const PROPERTY_NOT_INITIALIZED = "Свойство \"{property}\" не инициализировано. В класе \"{class}\".В пространстве имен \"{namespace}\".";
}