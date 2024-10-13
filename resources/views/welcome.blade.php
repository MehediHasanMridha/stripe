@extends('layouts.template')
@section('title') {{__("textes.accueil_page_nom")}}@endsection
@section('content')
    <!-- Div permettant d'avoir l'image et le texte à l'intérieur -->
    <div class="accueil-background">
        <h1 class="accueil-text mt-3">{{__("textes.accueil_titre")}}</h1>
    </div>

    <div class="d-flex align-items-center justify-content-center mt-4 mw-90">
        <p class="text-center text-index-size phone-margin">
            <b>{{__("textes.expertise_but_informatif")}}</b>
        </p>
    </div>

    <!-- Mise en place des boutons dans une div afin qu'ils soient affichés les uns en dessous des autres -->
    <div class="row row-cols-1 row-cols-sm-3 row-cols-md-4 my-5 mx-5 justify-content-center">
        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{route("formules.index")}}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-capsules icon-white pe-3"></i>
                <span class="btn-text"> {{__("textes.accueil_btn_formules")}} </span>
                <div></div>
            </div>
        </a>
        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{route("ingredients.index")}}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-leaf icon-white pe-3"></i>
                <span class="btn-text"> {{__("textes.accueil_btn_ingedients")}} </span>
                <div></div>
            </div>
        </a>

        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{route("expertise.index")}}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-stethoscope icon-white pe-3"></i>
                <span class="btn-text"> {{__("textes.accueil_btn_expertise")}} </span>
                <div></div>
            </div>
        </a>

        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{route("actualites.index")}}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-newspaper icon-white pe-3"></i>
                <span class="btn-text"> {{__("textes.accueil_btn_actualite")}} </span>
                <div></div>
            </div>
        </a>

        <a class="col btn btn-primary my-4 mx-1 " href="{{route("conseillers.index")}}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-user-md icon-white pe-3"></i> <span
                    class="btn-text"> {{__("textes.accueil_btn_conseillers")}} </span>
                <div></div>
            </div>
        </a>
        <a class="col btn btn-primary my-4 mx-1" href="{{route("compte.index")}}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-user-md icon-white pe-3"></i>
                <span class="btn-text"> {{__("textes.accueil_btn_compte")}} </span>
                <div></div>
            </div>
        </a>
        @level(5)
        <a class="col btn btn-primary my-4 mx-1" href="{{route("accueil.admin")}}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-tools icon-white pe-3"></i>
                <span class="btn-text"> {{__("textes.accueil_btn_admin")}} </span>
                <div></div>
            </div>
        </a>
        @endlevel
    </div>

    <!-- Section contenant les 3 dernières actualités -->
    <div class="container">
        <div class="row align-items-start">
            @foreach($actualites as $actualite)
                @if($actualite->status == 1)
                    <div class="col">
                        <div class="card my-4">
                            <div class="card-header">
                                <!--Affichage de la date-->
                                <em>{{ $actualite->date }}</em>
                            </div>
                            <div class="text-center mb-5">
                                <!--Affichage de l'image-->
                                @if(!empty($actualite->image))
                                    <img src="{{ asset($actualite->image) }}" class="actualite-image-width"
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
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
