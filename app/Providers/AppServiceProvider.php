<?php

namespace App\Providers;

use App\Support\CustomBackground;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

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
        View::composer('*', function (ViewInstance $view): void {
            $customBackgroundUrl = CustomBackground::url();

            $view->with([
                'customBackgroundUrl' => $customBackgroundUrl,
                'customBackgroundStyle' => CustomBackground::style($customBackgroundUrl),
            ]);
        });
    }
}
