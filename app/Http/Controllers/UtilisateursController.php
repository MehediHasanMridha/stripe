<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UtilisateursController extends Controller
{
    public function index(){
        $roles=config('roles.models.role')::where('slug', '!=', 'vip')->get();
        $vip_role_id = config('roles.models.role')::where('slug', '=', 'vip')->first()->id;
        $praticiens_id = config('roles.models.role')::where('slug', '=', 'praticien')->first()->id;

        $utilisateurs = DB::table("users")
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('role_user.role_id','!=',$vip_role_id)
            ->select('users.id','users.email','role_user.role_id','users.name','users.firstname','users.sub_end_at')

            ->get();

        return view('admin.utilisateurs.index',compact('utilisateurs','roles'));

    }

    public function edit(Request $request){
        $user = config('roles.models.defaultUser')::find($request->user_id);
        $vip=$user->hasRole('vip');
        $user->detachAllRoles();
        $user->attachRole($request->role_id);
        if($vip)
            $user->attachRole(config('roles.models.role')::where('slug', '=', 'vip')->first());
        return response("Ok",200);
    }

    public function vip(Request $request){
        $user = config('roles.models.defaultUser')::find($request->user_id);
        $vip = config('roles.models.role')::where('slug', '=', 'vip')->first();
        if($request->status === "true")
            $user->attachRole($vip);
        else
            $user->detachRole($vip);
        return response("Ok",200);
    }

    public function search(Request $request){
        $search=$request->search?:"";
        $roles=config('roles.models.role')::where('slug', '!=', 'vip')->get();
        $vip_role_id = config('roles.models.role')::where('slug', '=', 'vip')->first()->id;
        $utilisateurs = DB::table("users")
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('role_user.role_id','!=',$vip_role_id)
            ->select('users.id','users.email','role_user.role_id','users.name','users.firstname')
            ->get();
        $utilisateurs=$utilisateurs->filter(function($user) use ($search) {
            if(stripos($user->email,$search)!==false){
                return true;
            }
            elseif(stripos($user->name,$search)!==false){
                return true;
            }
            elseif(stripos($user->firstname,$search)!==false){
                return true;
            }
            else{
                return false;
            }
            /* return (stripos($user->email,$search)!==false); */
        });
        return view('admin.utilisateurs.index',compact('utilisateurs','roles','search'));
    }

    public function delete(Request $request){
        $userId=$request->userId;
        if($userId)
            DB::table('users')->where('id','=',$userId)->delete();
        return redirect()->route("utilisateurs.index");
    }

}
