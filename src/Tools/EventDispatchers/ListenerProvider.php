<?php

namespace Maris\JsonAnalyzer\Tools\EventDispatchers;

use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerProvider implements ListenerProviderInterface
{

    /**
     * @var array< class-string, array<int,callable> >
     */
    private array $listeners = [];

    /**
     * @inheritDoc
     */
    public function getListenersForEvent(object $event): iterable
    {
        return $this->listeners[$event::class] ?? [];
    }

    /**
     * Возвращает зарегистрированные события
     * который являются наследниками $parent
     * @param string $parent
     * @return array
     */
    public function getChildrenClasses( string $parent ):array
    {
        $result = [];
        foreach ($this->listeners as $eventType => $listener)
            if(is_a( $eventType, $parent, true)) $result[] = $eventType;
        return array_unique( $result );
    }



    /**
     * @param class-string $eventType
     * @param callable $callable
     * @return $this
     */
    public function addListener( string $eventType, callable $callable ): self
    {
        if(!is_a($eventType,Event::class,true))
            throw new \Exception("Событие не допустимо");

        $this->listeners[$eventType][] = $callable;
        return $this;
    }

    public function listenerExists( string $eventType ):bool
    {
        return isset( $this->listeners[$eventType] );
    }


    /**
     * @param string $eventType
     */
    public function clearListeners(string $eventType): void
    {
        if (array_key_exists($eventType, $this->listeners)) {
            unset($this->listeners[$eventType]);
        }
    }
}