<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Abonnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Price;
use Stripe\Stripe;


class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_KEY'));
        $prices=collect(Price::all(["product"=>env('STRIPE_PROD')])->toArray()["data"])->where("active",true);

        $prices->map(function($price){
            if(strcmp($price["recurring"]["interval"], "month")===0)$this->month=$price;
            elseif (strcmp($price["recurring"]["interval"], "year")===0)$this->year=$price;
        });
    }


    public function index() {

        // dd($this->month,$this->year);
        if(Abonnement::isSub()) return redirect()->route('stripe.createCustomerPortalSession');
        else return view('stripe.index', ["month"=>$this->month,"year"=>$this->year]);
    }

    public function checkout($price) {
        if(Abonnement::isSub())return back();
        App::setLocale(\Illuminate\Support\Facades\Session::get("locale"));

        $user = Auth::user();

        $session = Session::create([
            'success_url' => route("stripe.success").'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route("stripe.cancel"),
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'customer'=> $user->stripe_id,
            'customer_email' => $user->stripe_id?null:$user->email,
            'line_items' => [[
                'price' => $price,
                // For metered billing, do not pass quantity
                'quantity' => 1,
            ]],
        ]);
        redirect()->to($session->url)->send();
    }
    public function success(Request $request) {
        return view('stripe.success');
    }

    public function cancel() {
        return view('stripe.cancel');
    }

    public function createCustomerPortalSession() {
        // Authenticate your user.
        $session = \Stripe\BillingPortal\Session::create([
          'customer' => Auth::user()->stripe_id,
          'return_url' => route('compte.index'),
        ]);

        // Redirect to the customer portal.
        header("Location: " . $session->url);
        exit();
    }
}
