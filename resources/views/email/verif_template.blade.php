@extends('layouts.email')
@section('content')
    <div class="content-email">
        <h1 class="title-email">Vérification de votre adresse email</h1>

        <p class="text-email">Veuillez cliquer sur le bouton ci-dessous pour valider votre inscription :</p>

        <div class="wrap-bouton">
            <a class="bouton-email" href="{{$data['url']}}">
                <span> Validez votre inscription </span>
            </a>
        </div>
        <p class="text-email">Sunsimiao est informé de votre demande de validation<br>Si vous n'avez pas créé de compte
            aucune action n'est requise.</p>
    </div>
@endsection
