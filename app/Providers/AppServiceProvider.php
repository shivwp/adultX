<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Menu;
use App\Models\Setting;
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
        View::composer('*', function($view){
                 $logo=Setting::pluck('value', 'name');
                 $navmenu= Menu::get();
                 $view->with('navmenu', $navmenu);
                 $view->with('logo',$logo);
        });
    }
}
