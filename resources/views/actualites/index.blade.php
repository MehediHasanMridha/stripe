@extends('layouts.template')
@section('title'){{ __("textes.actualites_page_nom") }}@endsection
@section('content')
    <section>
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_actualites") }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center justify-content-center">
            <!-- Affiche le titre de la page -->
            <h2 class="text-center my-3">{{ __("textes.actualites_index_titre") }}</h2>
        </div>
        <div class="container">
            @foreach($actualites as $actualite)
                @if($actualite->status == 1)
                    <div class="card my-4">

                        <div class="card-header">
                            <!--Affichage de la date-->
                            <em>{{ $actualite->date }}</em>
                        </div>

                        <div class="text-center mb-5">
                            <!--Affichage de l'image-->
                            @if(!empty($actualite->image))
                                <img src="{{ asset($actualite->image) }}"
                                     class="actualite-image-width"
                                     alt="Image de l'actualité '{{ $actualite->titre }}'">
                            @else
                                <em class="text-muted fs-5 text-center">{{ __("textes.actualites_index_image_aucun") }}</em>
                            @endif
                        </div>

                        <div class="card-body">
                            <!--Affichage du titre du résumé et du paragraphe-->
                            <h4>{{ $actualite->titre }}</h4>
                            @if (!empty($actualite->resume))
                                <p class="card-text">{{ $actualite->resume }}</p>
                            @else
                                <p class="card-text">{!!  substr(html_entity_decode($actualite->paragraphe, ENT_HTML5, 'UTF-8'), 0, 100) !!}
                                    [...]</p>
                            @endif
                        </div>

                        <div class="card-footer">
                            <a href="{{ route('actualites.show', [$actualite->id]) }}"
                               class="btn btn-medium text-white">{{ __("textes.actualites_index_btn_lire") }}</a>
                        </div>

                    </div>
            @endif
        @endforeach
        </div>
    </section>
@endsection
