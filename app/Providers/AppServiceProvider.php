<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Infrastructure\Persistence\EloquentTaskRepository;
use App\Infrastructure\Events\Dispatcher;
use App\Domain\Events\TaskWasCreated;
use App\Models\TaskEventLog;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);

        $this->app->singleton(Dispatcher::class, function () {
        $dispatcher = new Dispatcher();

        // Логирование
        $dispatcher->listen(TaskWasCreated::class, function (TaskWasCreated $event) {
            logger()->info("[LOG] Задача создана: " . $event->task->title);
        });

        // Уведомление
        $dispatcher->listen(TaskWasCreated::class, function (TaskWasCreated $event) {
            // В реальности здесь можно вызвать Mail, TelegramBot, и т.п.
            logger()->info("[NOTIFY] Уведомление: '{$event->task->title}' создана.");
        });

        //Сделаем аналогично для TaskWasUpdated, TaskWasDeleted позже.
        $dispatcher->listen(TaskWasCreated::class, function (TaskWasCreated $event) {
        TaskEventLog::create([
            'task_id' => $event->task->id,
            'event_type' => TaskWasCreated::class,
            'payload' => [
                'title' => $event->task->title,
                'description' => $event->task->description,
                'user_id' => $event->task->userId,
            ],
            'created_at' => now(),
             ]);
        });

        return $dispatcher;
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
