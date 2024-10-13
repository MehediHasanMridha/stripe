@extends('layouts.template')
@section('title') {{ __("textes.actualites_page_nom") }} @endsection
@section('content')
    <section>
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a class="arianne-link" href="{{ route('actualites.index') }}">{{ __("textes.arianne_actualites") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $actualite->titre }}</li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <div class="d-flex justify-content-between">
                <div class="text-border-space-left"></div>

                <!--Affichage de l'image-->
                @if(!empty($actualite->image))
                    <img src="{{ asset($actualite->image) }}"
                         alt="L'image de l'article : {{ $actualite->titre }}"
                         class="actualite-image-show-width">
                @else
                    <em class="text-muted fs-5">{{ __('textes.actualites_show_image_aucun') }}</em>
            @endif

            <!-- Affiche une îcone qui ramène l'utilisateur en arrière -->
                <button type="button" onclick="window.history.go(-1)" class="btn-close ms-2 x-size"
                        aria-label="Close"></button>
            </div>
            <!--Affichage du titr et des catégories-->
            <h2 class="fw-bold my-4">{{ $actualite->titre }}</h2>
            <div class="d-flex align-items-center justify-content-start my-4">
                @foreach($actualite->categories as $categorie)
                    <span class="badge bg-secondary fs-6 mx-1">{{ $categorie }}</span>
                @endforeach
            </div>
            <p>{!! html_entity_decode($actualite->paragraphe, ENT_HTML5 ,'UTF-8') !!}</p>
        </div>
    </section>
@endsection
