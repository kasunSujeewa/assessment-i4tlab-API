<?php

namespace App\Providers;

use App\Contracts\TaskService;
use App\Models\Task;
use App\Services\AdminTaskService;
use App\Services\UserTaskService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app->bind(TaskService::class, function ($app) {
            $user = Auth::guard('api')->user();
            if ($user && $user->role === 'Admin') {
                return $app->make(AdminTaskService::class);
            }
    
            return $app->make(UserTaskService::class);
           
        });
    }
}
