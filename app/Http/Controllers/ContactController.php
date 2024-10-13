<?php

namespace App\Http\Controllers;



use App\Mail\ContactMail;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;


class ContactController extends Controller
{
    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Retourne la vue contact.blade.php du dossier footer
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('footer.contact');
    }

    public function send(Request $request){
        $this->validate($request, [
            'sujet'     =>  'required',
            'message' =>  'required'
        ]);
        $user = Auth::user();

        $data = array(
            'sujet'      =>  $request->name,
            'message'   =>   $request->message,
            'email' => $user
        );

        Mail::to('contact@sunsimiao.fr')->send(new ContactMail($data));
        return back()->with('success', 'Merci, votre message a bien été envoyé.');
    }
}
