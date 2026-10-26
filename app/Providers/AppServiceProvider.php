<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        View::composer('components.navbar.navbar', function (\Illuminate\View\View $view): void {
            $user = request()->user();

            $view->with('unreadNotificationsCount', $user?->unreadNotifications()->count() ?? 0);
            $view->with(
                'recentNotifications',
                $user?->notifications()
                    ->reorder()
                    ->orderByDesc('created_at')
                    ->orderByDesc('id')
                    ->limit(5)
                    ->get() ?? collect(),
            );
        });
    }
}
