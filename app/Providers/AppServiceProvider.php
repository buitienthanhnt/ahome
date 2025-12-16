<?php

namespace App\Providers;

use App\Helper\HelperFunction;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
         $this->app->singleton(HelperFunction::class, function (Application $app) {
            return new HelperFunction();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Blade::directive('render', function ($component) {
            //  * @render(\App\ViewBlock\TopCategory::class)
            return "<?php echo (app($component))->toHtml(); ?>";
            // $html = (app($component))->toHtml();
            // return $html;
        });
    }
}
