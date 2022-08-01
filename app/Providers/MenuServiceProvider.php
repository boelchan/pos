<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $menuJson = file_get_contents(base_path('resources/data/menu.json'));
        $menuData = json_decode($menuJson);

        // Share all menuData to all the views
        \View::share('menuData', $menuData);
    }
}
