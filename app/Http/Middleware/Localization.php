<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Localization {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        }
        else {
            if(Auth::check()) {
                session()->put('locale', Auth::user()->language);
                App::setLocale(Auth::user()->language);
            }
            else {
                session()->put('locale', App::getLocale());
            }
        }
        return $next($request);
    }
}
