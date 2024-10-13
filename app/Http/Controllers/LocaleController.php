<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function handle($lang): \Illuminate\Http\RedirectResponse
    {
        Session::put('locale',$lang);
        return back();
    }
}
