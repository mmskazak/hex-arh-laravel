<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskEventLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PruneTaskEventLogs extends Command
{
    protected $signature = 'eventlog:prune 
                            {--days=180 : Delete events older than N days} 
                            {--dry-run : Показывает, сколько записей будет удалено, без удаления}';

    protected $description = 'Удаляет записи task_event_logs старше N дней (или просто показывает, если --dry-run)';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        $cutoff = Carbon::now()->subDays($days);

        $query = TaskEventLog::where('created_at', '<', $cutoff);

        $count = $query->count();

        if ($count === 0) {
            $this->info("Нет записей старше {$cutoff->toDateTimeString()}.");
            Log::info("[eventlog:prune] Нет записей старше {$cutoff->toDateTimeString()}.");
            return Command::SUCCESS;
        }

        if ($dryRun) {
            $this->info("Найдено $count записей, которые БЫЛИ БЫ удалены (dry-run).");
            Log::info("[eventlog:prune] Dry-run: $count записей старше {$cutoff->toDateTimeString()}.");
        } else {
            $deleted = $query->delete();
            $this->info("Удалено $deleted записей старше {$cutoff->toDateTimeString()}.");
            Log::info("[eventlog:prune] Удалено $deleted записей старше {$cutoff->toDateTimeString()}.");
        }

        return Command::SUCCESS;
    }
}
