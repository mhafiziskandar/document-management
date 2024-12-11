<?php

namespace App\Providers;

use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class HeaderComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        view()->composer('layouts.master', function ($view) {
            $query = File::select('folder_id')
                        ->where("status", File::PENDING)
                        ->distinct();

            if (!Auth::user()->hasRole('admin')) {
                $query->where('user_id', Auth::id());
            }

            $pendingFoldersCount = $query->count('folder_id');

            $view->with('pendingFoldersCount', $pendingFoldersCount);
        });
    }
}
