<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Application;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    protected $app;
    protected $request;

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }
    public function handle(Request $request, Closure $next)
    {
        if(Session::get("locale") != null) {
            App::setLocale(Session::get('locale'));
        } else {
            Session::put('locale', 'id');
            App::setLocale(Session::get('locale'));
        }

        // if(Session::has('locale')) {
        //     App::setLocale(Session::get('locale'));
        // }

        // $this->app->setLocale(session('my_locale', config('app.locale')));

        return $next($request);
    }
}
