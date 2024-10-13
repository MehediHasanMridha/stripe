@extends('layouts.template')
@section('title'){{__("textes.conseillers_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a class="arianne-link" href="{{ route('conseillers.index') }}">{{ __("textes.arianne_conseillers") }}</a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">{{ __("textes.arianne_recherche_conseillers") }}</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col phone-margin">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="title-size">{{ __("textes.conseillers_liste_search") }}</h2>

                        <!-- Affiche une îcone qui ramène l'utilisateur vers la page des options pour trouver un praticien -->
                        <a href="{{ route('conseillers.index') }}">
                            <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                        </a>
                    </div>

                    <form action="{{ route('conseillers.liste') }}" class="mt-5">
                        <!--Affichage de "rechercher par :" -->
                        <h3 class="h3 mb-5">{{ __("textes.conseillers_listeform_search_par") }}</h3>

                        <!--Choix du département-->
                        <h4><label for="departement"
                                   class="form-label">{{__("textes.conseillers_listeform_departement")}}</label></h4>
                        <select name="departement" id="departement" class="form-select mt-2 text-input">
                            <option selected hidden>{{__("textes.conseillers_listeform_departement")}}</option>
                            @foreach($departements as $departement)
                                <option value="{{ $departement->departement }}">{{ $departement->departement }}</option>
                            @endforeach
                        </select>

                        <!--Choix de la région-->
                        <h4><label for="region"
                                   class="form-label mt-4">{{__("textes.conseillers_listeform_region")}}</label></h4>
                        <select name="region" id="region" class="form-select mt-2 text-input">
                            <option selected hidden>{{__("textes.conseillers_listeform_region")}}</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->region }}">{{ $region->region }}</option>
                            @endforeach
                        </select>

                        <!--Choix mot clé-->
                        <h4><label for="autre"
                                   class="form-label mt-4">{{__("textes.conseillers_listeform_mot_cle")}}</label></h4>
                        <input type="text" name="autre" class="form-control text-input" id="autre"
                               placeholder="{{__("textes.conseillers_listeform_mot_cle_placeholder")}}">

                        <!--Bouton de validation-->
                        <div class="d-flex justify-content-center justify-content-sm-start">
                            <button type="submit" class="btn btn-medium mt-5">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-search icon-white pe-3"></i>
                                    <span class="btn-text"> {{ __("textes.conseillers_listeform_search") }} </span>
                                    <div></div>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
