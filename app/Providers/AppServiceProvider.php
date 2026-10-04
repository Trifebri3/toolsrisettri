<?php

namespace App\Providers;

use App\Models\ResearchProject;
use Illuminate\Support\Facades\Auth;
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
        View::composer(['layouts.navigation', 'layouts.app', 'dashboard', 'projects.*'], function ($view) {
            if (Auth::check()) {
                $userId = Auth::id();
                $projects = ResearchProject::where('user_id', $userId)
                    ->select(['id', 'title', 'field', 'status'])
                    ->withCount(['claims', 'evidences', 'outputs'])
                    ->latest()
                    ->take(15)
                    ->get();
                $view->with('navProjects', $projects);
            } else {
                $view->with('navProjects', collect());
            }
        });
    }
}
