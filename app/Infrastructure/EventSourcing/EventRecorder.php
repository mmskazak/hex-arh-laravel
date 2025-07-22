<?php

namespace App\Infrastructure\EventSourcing;

class EventRecorder
{
    public function record(string $aggregateId, object $event): void
    {
        StoredEvent::create([
            'aggregate_id' => $aggregateId,
            'aggregate_type' => get_class($event),
            'event_type' => get_class($event),
            'payload' => $this->serialize($event),
            'occurred_at' => now(),
        ]);
    }

    private function serialize(object $event): array
    {
        return json_decode(json_encode($event), true);
    }
}
