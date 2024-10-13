<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('recaptcha_validator', function ($attribute, $value, $parameters, $validator) {
            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_secret = '6Lf9DfUdAAAAAPZkOJ4jWGMuuOBuqvnO_1eCUjnC';


            $inputs = $validator->getData();
            $recaptcha_response = $inputs['recaptcha_response'];


            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            $recaptcha = json_decode($recaptcha);

            if(empty($recaptcha->score))return false;

            if ($recaptcha->score >= 0.5) {
                return true;
            }else{
                return false;
            }
        });

    }
}
