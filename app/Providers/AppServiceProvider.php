<?php

namespace App\Providers;

use App\Models\SubTask;
use App\Models\Task;
use App\Observers\SubtaskObserver;
use App\Observers\TaskObserver;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Task::observe(TaskObserver::class);
        SubTask::observe(SubtaskObserver::class);
        require_once app_path('utils/helpers.php');
    }
}
