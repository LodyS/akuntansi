<?php
namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cookie;

class SetSessionLength 
{
    const SESSION_LIFETIME_PARAM = 'sessionLifetime';
    const SESSION_LIFETIME_DEFAULT_MINS = 5000000;

    public function handle($request, $next) 
    {
        $lifetimeMins = Cookie::get(self::SESSION_LIFETIME_PARAM, $request->input(self::SESSION_LIFETIME_PARAM)); 
        if ($lifetimeMins) {
            Cookie::queue(self::SESSION_LIFETIME_PARAM, $lifetimeMins, $lifetimeMins); 
            config(['session.lifetime' => $lifetimeMins]);
        }
        return $next($request);
    }
}