<?php

namespace App\Providers;

use App\Contracts\TaskService;
use App\Services\AdminTaskService;
use App\Services\UserTaskService;
use Illuminate\Support\Facades\Auth;
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
        $this->app->bind(TaskService::class, function ($app) {
            $user = Auth::guard('api')->user();

        if ($user && $user->role == 'Admin') {
            return new AdminTaskService();
        } else {
            return new UserTaskService();
        }
           
        });
    }
}
