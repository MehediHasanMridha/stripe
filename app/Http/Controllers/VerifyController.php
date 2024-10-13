<?php

namespace App\Http\Controllers;

class VerifyController extends Controller
{
    public function email(){
        if (auth()->user()->level() >= 2)
            return redirect()->route("compte.index");
        return view("auth.verify-email");
    }

    public function conseiller(){
        if (auth()->user()->level() >= 3)
            return redirect()->route("compte.index");
        return view("auth.verify-conseiller");
    }
}
