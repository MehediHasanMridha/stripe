@extends('layouts.admin-template')
@section('title'){{__("textes.admin_page_nom")}}@endsection
@section('content')
    <div class="accueil-background">
        <h1 class="accueil-text mt-3">{{__("textes.admin_titre")}}</h1>
    </div>

    <!-- Mise en place des boutons dans une div afin qu'ils soient affichés les uns en dessous des autres -->
    <div class="row row-cols-1 row-cols-sm-3 row-cols-md-4 my-5 mx-5 justify-content-center">

        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{ route('symptomes.index') }}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-thermometer-three-quarters icon-white pe-3"></i> <span
                    class="btn-text"> {{__("textes.admin_btn_symptomes")}} </span>
                <div></div>
            </div>
        </a>

        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{ route('syndromesAdmin.index') }}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-virus icon-white pe-3"></i> <span
                    class="btn-text"> {{__("textes.admin_btn_syndromes")}} </span>
                <div></div>
            </div>
        </a>

        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{ route('ingredientsAdmin.index') }}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-leaf icon-white pe-3"></i> <span
                    class="btn-text"> {{__("textes.admin_btn_ingredients")}} </span>
                <div></div>
            </div>
        </a>

        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{ route('formulesAdmin.index') }}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-capsules icon-white pe-3"></i> <span
                    class="btn-text"> {{__("textes.accueil_btn_formules")}} </span>
                <div></div>
            </div>
        </a>

        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{ route('traductions.index') }}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-language icon-white pe-3"></i> <span
                    class="btn-text"> {{__("textes.admin_btn_traduction")}} </span>
                <div></div>
            </div>
        </a>

        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{ route('recherche.index') }}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-search icon-white pe-3"></i> <span
                    class="btn-text"> {{ __("textes.headerAdmin_link_rechercher") }} </span>
                <div></div>
            </div>
        </a>
        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{ route('conseillersAdmin.index') }}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-user-md icon-white pe-3"></i> <span
                    class="btn-text"> {{__('textes.admin_conseillers')}} </span>
                <div></div>
            </div>
        </a>
        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{ route('utilisateurs.index') }}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-address-book icon-white pe-3"></i> <span
                    class="btn-text"> {{__('textes.admin_utilisateurs')}} </span>
                <div></div>
            </div>
        </a>
        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{ route('actualitesAdmin.index') }}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-newspaper icon-white pe-3"></i> <span
                    class="btn-text"> {{__('textes.admin_actualites')}} </span>
                <div></div>
            </div>
        </a>
        <a class="col btn btn-primary my-4 mx-1 btn-size" href="{{ route('accueil.index') }}">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-home icon-white pe-3"></i> <span
                    class="btn-text"> {{__("textes.admin_btn_accueil")}} </span>
                <div></div>
            </div>
        </a>
    </div>
@endsection
