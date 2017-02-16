<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LanguageController;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Session::has('applocale') and in_array(Session::get('applocale'), ['en', 'ee', 'ru'])) {
            App::setLocale(Session::get('applocale'));
        } else {
            

            $userLang = 'en';

            Session::set('applocale', $userLang);

            App::setLocale($userLang);
        }

        return $next($request);
    }
}
