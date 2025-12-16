<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class LanguageProvider extends ServiceProvider
{
    const LANGUAGE_SESSION = 'LANGUAGE_SESSION';

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
        //
    }

    /**
     * use after session function start.
     * return current locale after set language.
     * @param string $locale
     * @return string
     */
    public static function applyLanguage(string $locale): string
    {
        Session::put(self::LANGUAGE_SESSION, $locale);
        Session::save();
        App::setLocale($locale);
        return App::currentLocale();
    }

    /**
     * clear session language setup value
     * so, app will run with default language
     */
    public static function resetLanguage(): string {
        Session::forget(self::LANGUAGE_SESSION);
        Session::save();
        return config('app.locale');
    }

    /**
     * check session language and current language
     * if value of them not equal, app will use value of session value.
     */
    public static function bootLanguage(): void {
        $sessionLang = Session::get(self::LANGUAGE_SESSION);
        if (!$sessionLang) {
            return;
        }

        if ($sessionLang !== App::currentLocale()) {
            App::setLocale($sessionLang);
        }
    }
}
