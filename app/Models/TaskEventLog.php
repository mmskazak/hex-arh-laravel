<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class TaskEventLog extends Model
{
    protected $table = 'task_event_logs';
    public $timestamps = false;

    protected $fillable = ['task_id', 'event_type', 'payload', 'created_at'];

    protected $casts = [
        'payload' => 'array',
    ];

    public function history(int $id): JsonResponse
    {
        $logs = TaskEventLog::where('task_id', $id)
            ->orderBy('created_at')
            ->get()
            ->map(function ($log) {
                return [
                    'event' => class_basename($log->event_type),
                    'data' => $log->payload,
                    'at' => $log->created_at,
                ];
            });

        return response()->json(['history' => $logs]);
    }
}
