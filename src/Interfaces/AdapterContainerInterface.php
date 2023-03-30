<?php

namespace Maris\JsonAnalyzer\Interfaces;

use Maris\JsonAnalyzer\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;

/**
 * Интерфейс для контейнера хранящего в себе адаптеры
 */
interface AdapterContainerInterface extends ContainerInterface
{
    /**
     * Добавляет адаптер в контейнер
     * @param string|class-string $type
     * @param JsonAdapterInterface $adapter
     * @return $this
     * @throws ContainerException
     */
    public function set( string $type , JsonAdapterInterface $adapter ):static;

    /**
     * Получает адаптер из контейнера
     * @param string|class-string $id
     * @return JsonAdapterInterface
     */
    public function get( string $id ):JsonAdapterInterface;
}