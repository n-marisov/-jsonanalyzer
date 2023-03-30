<?php

namespace Maris\JsonAnalyzer\Tools\EventDispatchers;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Класс родитель для всех событий
 */

abstract class Event implements StoppableEventInterface
{
    private bool $propagationStopped = false;

    /**
     * @inheritDoc
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }
}