<?php

namespace App\Providers;

use App\Models\SubTask;
use App\Models\Task;
use App\Models\Timesheet;
use App\Observers\SubtaskObserver;
use App\Observers\TaskObserver;
use App\Policies\ProjectPolicy;
use App\Policies\UserPolicy;
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

        Gate::define('create-project', [ProjectPolicy::class, 'create']);
        Gate::define('update-project', [ProjectPolicy::class, 'update']);
        Gate::define('delete-project', [ProjectPolicy::class, 'create']);

        Gate::define('create-user', [UserPolicy::class, 'create']);
        Gate::define('update-user', [UserPolicy::class, 'update']);
        Gate::define('delete-user', [UserPolicy::class, 'create']);

        Gate::define('timesheet_detail', function($user, $model) {
          return $user->id === $model->id ||
                $user->role === 'admin' ||
                ($user->role === 'manager' && $model->department_id === $user->department_id);
        });

        Gate::define('check_in', function($user) {
          return $user->role === 'member' && Timesheet::where('user_id', $user->id)->whereDate('date', now())->doesntExist();
        });

        Gate::define('check_out', function($user) {
            return $user->role === 'member' && Timesheet::where('user_id', $user->id)->whereDate('date', now())->whereNull('check_out_time')->exists();
        });
    }
}
