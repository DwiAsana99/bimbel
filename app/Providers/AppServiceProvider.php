<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
// use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        if(env('APP_ENV') == 'production') {
            \URL::forceScheme('https');
        }
      // $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
      //     $event->menu->add([
      //         'text'        => 'Tabungan',
      //         'url'         => 'admin/users',
      //         'icon'        => 'users',
      //         'label'       => '',
      //         'label_color' => 'success',
      //     ]);
      // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
