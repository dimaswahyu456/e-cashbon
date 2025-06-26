<?php

namespace App\Providers;

use App\Models\Approved;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

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
        View::composer('layouts.sidebar', function ($view) {
            $countapprove = DB::connection('firebird')
                    ->table("CASHBON_LPJ")
                    ->select('STATUS_APPROVED')
                    ->where('STATUS_APPROVED', '1')
                    ->count();
            $countreject = DB::connection('firebird')
                    ->table("CASHBON_LPJ")
                    ->select('STATUS_APPROVED')
                    ->where('STATUS_APPROVED', '0')
                    ->count();

            $view->with([
                'countapprove' => $countapprove,
                'countreject' => $countreject
            ]);
        });
    }
}
