<?php

namespace App\Providers;

use App\View\Components\Adminhtml\DashboardChart;
use App\View\Components\Adminhtml\SideBar;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewShareProvider extends ServiceProvider
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
    public function boot(): void
    {
        /**
         * admin left side-bar
         */
        Blade::component('side-bar', SideBar::class);

        /**
         * https://laravel.com/docs/11.x/blade#components
         * dashboard top chart component
         * php artisan make:component Adminhtml\DashboardChart
         * create new view blade file in view/components/adminhtml/
         */
        Blade::component('dashboard-chart', DashboardChart::class);
    }
}
