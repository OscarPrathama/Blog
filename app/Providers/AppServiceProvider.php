<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        view()->composer(
            'layouts.navbar',
            function ($view) {
                $main_menu = \App\Models\Menu::where('menu_position', '=', 'main_menu')->first();
                $view->with('main_menu', json_decode($main_menu['menus']));
            }
        );

    }
}
