<?php

namespace App\Infrastructure\Events;

class Dispatcher
{
    private array $listeners = [];

    public function listen(string $eventClass, callable $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }

    public function dispatch(object $event): void
    {
        $class = get_class($event);

        foreach ($this->listeners[$class] ?? [] as $listener) {
            $listener($event);
        }
    }
}
