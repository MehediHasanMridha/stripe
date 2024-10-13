@extends('layouts.template')
@section('title') {{__("textes.conseillers_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_conseillers") }}</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="d-flex align-items-center justify-content-center">
                        <!-- Affiche le titre de la page -->
                        <h2 class="text-center my-3">{{ __("textes.conseillers_index_titre") }}</h2>
                    </div>
                    <!--Affichage du texte en haut de page-->
                    <p class="text-index-size phone-margin">{{ __("textes.conseillers_index_text") }}</p>

                    <div class="d-flex flex-column justify-content-center align-items-center mt-4">
                        <!--Affichage du bouton code-->
                        <a class="btn btn-large mb-4" href="{{ route('conseillers.code') }}">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-hashtag icon-white pe-3"></i> <span
                                    class="btn-text"> {{ __("textes.conseillers_index_btn_code") }} </span>
                                <div></div>
                            </div>
                        </a>
                        <!--Affichage du bouton liste-->
                        <a class="btn btn-large my-4" href="{{ route('conseillers.listeForm') }}">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-clipboard-list icon-white pe-3"></i> <span
                                    class="btn-text"> {{ __("textes.conseillers_index_btn_liste") }} </span>
                                <div></div>
                            </div>
                        </a>
                        <!--Affichage du bouton localisation-->
                        <a class="btn btn-large mt-4" href="{{ route('conseillers.localisation') }}">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-map-marker-alt icon-white pe-3"></i> <span
                                    class="btn-text"> {{ __("textes.conseillers_index_btn_localisation") }} </span>
                                <div></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-between-footer"></div>
    </section>
@endsection
