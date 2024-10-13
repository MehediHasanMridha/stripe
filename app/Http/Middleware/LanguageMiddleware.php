<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
{
    protected $defaultLang = "fr";
    /**
     * Middleware qui s'occupe d'appliquer la langue sur chaque page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Session::has('locale'))
            Session::put('locale',$this->defaultLang);
        App::setLocale(Session::get('locale'));
        return $next($request);
    }
}
