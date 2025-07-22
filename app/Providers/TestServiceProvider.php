<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Infrastructure\Persistence\InMemoryTaskRepository;

class TestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TaskRepositoryInterface::class, function () {
            return new InMemoryTaskRepository();
        });
    }
}
