<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\Abonnement;
use Illuminate\Support\Facades\DB;


class CompteController extends Controller
{
    public function index(){
        return view('compte.index', ["sub"=>Abonnement::isSub()]);
    }

    public function updateEmail(Request $request){
        $user=Auth::user();
        $user->email=$request->email;
        $user->save();

        return redirect()->back()->with('success', 'Email modifié !');
    }

    public function updatePassword(Request $request){
        if($request->password_confirm!=$request->password){
            return redirect()->back()->with('failed', 'Le nouveau mot de passe et ça confirmation ne sont pas identiques!');
        }
        if(Hash::check($request->old_password, auth()->user()->password)){
            $user=Auth::user();
            $user->password=Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success', 'Mot de passe modifié !');
        }
        else{
            return redirect()->back()->with('failed', 'L\'ancien mot de passe n\'est pas correct !');
        }
    }
    public function delete(){
        return redirect()->route("compte.index")->with("deleted",true);
    }
    public function deleteUser(){
        $user = User::find(Auth::user()->id);
        if($user->delete())
            return response('Compte Supprime', 200)
                ->header('Content-Type', 'text/plain');
    }
}
