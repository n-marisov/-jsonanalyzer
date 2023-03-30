<?php

namespace Maris\JsonAnalyzer\Tools\EventDispatchers;

use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class Dispatcher implements EventDispatcherInterface
{
    public readonly ListenerProvider $provider;


    public function __construct()
    {
        $this->provider = new ListenerProvider();
    }


    /**
     * @template T as object
     * @param object<?T> $event
     * @return T
     * @throws Exception
     */
    public function dispatch( object $event ):object
    {
        if( !is_a($event,Event::class) )
            throw new Exception("Событие не допустимо");

        if( is_a($event,StoppableEventInterface::class) && $event->isPropagationStopped() )
            return $event;
        foreach ($this->provider->getListenersForEvent($event) as $listener)
            $listener($event);
        return $event;
    }
}