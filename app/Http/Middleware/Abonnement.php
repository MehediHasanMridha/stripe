<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Stripe\Customer;
use Stripe\Stripe;

class Abonnement
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
        if(!Auth::user())
            return redirect()->route('login');
        if(!($this->isSub()) && !Auth::user()->hasRole('admin'))
            return redirect()->route('stripe.index');
        return $next($request);
    }

    public static function isSub(){
        $user=Auth::user();
        if($user->hasRole('vip'))
            return true;
        if($user->sub_end_at)
            return $user->sub_end_at>time();
        return false;
    }
}
