<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifyConseillerController extends Controller
{
    public function index(Request $request){
        return view("auth.verify-conseiller");
    }
}
