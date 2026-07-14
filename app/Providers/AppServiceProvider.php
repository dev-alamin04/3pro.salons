<?php

namespace App\Providers;

use App\Models\SystemSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->singleton(AnalyticsEventsService::class, function ($app) {
        //     return new AnalyticsEventsService();
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $systemSetting = SystemSetting::first();
            $view->with('systemSetting', $systemSetting);
        });

        Gate::define('is_active', function ($user) {
            return $user->is_active;
        });

        Model::retrieved(function (Model $model) {
            $timezone = Auth::check()
                ? (Auth::user()->timezone ?? config('app.timezone'))
                : config('app.timezone');

            if ($timezone === config('app.timezone')) {
                return; 
            }

            foreach ($model->getDates() as $field) {
                if (! empty($model->{$field})) {
                    $model->{$field} = $model->{$field}->copy()->setTimezone($timezone);
                }
            }
        });
    }
}