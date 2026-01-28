<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Carbon\Carbon;
use Route;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	Schema::defaultStringLength(191);
        Blade::if('segretaria', function(){
            return auth()->guard('segretarie')->check();
        });
		Blade::if('admin', function(){
			return auth()->guard('admin')->check();
		});
        Blade::if('amministratori', function(){
            return auth()->guard('admin')->check() || auth()->guard('segretarie')->check();
        });
		Blade::if('auth', function(){
            return session()->has('guard') && auth()->guard(session('guard'))->check();
		});
        Blade::if('docente', function(){
            return auth()->guard('docenti')->check();
        });
        Blade::if('allievo', function(){
            return auth()->guard('allievi')->check();
        });
		// Setto in italiano le date
		setlocale(LC_TIME, 'it_IT');
		Carbon::setLocale(config('app.locale'));
        Blade::directive('group', function ($element) {
            return '<?php $r = explode(".", Route::currentRouteName()); echo (strcmp('.$element.', $r[0]) === 0 ? \' opened\' : "") ?>';
        });
        Blade::directive('tab', function ($element) {
            return '<?php $r = explode(".", Route::currentRouteName()); echo (strcmp('.$element.', $r[1].(isset($r[2]) ? ".".$r[2] : "").(isset($r[3]) ? ".".$r[3] : "").(isset($r[4]) ? ".".$r[4] : "")) === 0 ? \' opened\' : "" ) ?>';
        });
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
