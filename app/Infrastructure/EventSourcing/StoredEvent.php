<?php

namespace App\Infrastructure\EventSourcing;

use Illuminate\Database\Eloquent\Model;

class StoredEvent extends Model
{
    protected $table = 'stored_events';
    public $timestamps = false;

    protected $fillable = [
        'aggregate_id',
        'aggregate_type',
        'event_type',
        'payload',
        'occurred_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'occurred_at' => 'datetime',
    ];
}
