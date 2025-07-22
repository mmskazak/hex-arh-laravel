<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskEventLog;
use Illuminate\Support\Carbon;

class PruneTaskEventLogs extends Command
{

    protected $signature = 'eventlog:prune {--days=180 : Delete events older than N days}';

    protected $description = 'Удаляет записи task_event_logs старше N дней';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = Carbon::now()->subDays($days);

        $count = TaskEventLog::where('created_at', '<', $cutoff)->delete();

        $this->info("Удалено $count записей старше {$cutoff->toDateTimeString()}.");

        return Command::SUCCESS;
    }
}
