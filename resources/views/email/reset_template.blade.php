@extends('layouts.email')
@section('content')
    <div class="content-email">
        <h1 class="title-email">Réinitialisation de votre mot de passe</h1>

        <p class="text-email">Veuillez cliquer sur le bouton ci-dessous pour réinitialiser votre mot de passe :</p>

        <div class="wrap-bouton">
            <a class="bouton-email" href="{{$data['url']}}">
                <span> Réinitialisez votre mot de passe </span>
            </a>
        </div>
        <p class="text-email">Si vous n'êtes pas à l'origine de cette demande, aucune action n'est requise.</p>
    </div>
@endsection
