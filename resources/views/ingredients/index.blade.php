@extends('layouts.template')
@section('title'){{__("textes.ingredients_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_ingredients") }}</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="mb-5">
                        <div class="d-flex align-items-center justify-content-center">
                            <!-- Affiche le titre de la page -->
                            <h2 class="text-center my-3">{{ __("textes.ingredients_index_titre") }}</h2>
                        </div>
                        <!-- Texte qui va expliquer quelque chose par la suite -->
                        <p class="text-index-size phone-margin">
                            {{__("textes.ingredients_index_text")}}
                        </p>

                        <!-- Boutons qui amène l'utilisateur vers à la page contenant la liste de tous les ingrédients, avec comme argument la langue sélectionnée -->
                        <div class="d-flex flex-column align-items-center mt-4 mx-5">
                            <a href="{{ route('ingredients.liste', ['cn']) }}" class="btn btn-large">
                                <span class="btn-text">{{ __("textes.ingredients_index_btn_text_zh") }}</span>
                            </a>
                            <a href="{{ route('ingredients.liste', ['fr']) }}" class="btn btn-large">
                                <span class="btn-text">{{ __("textes.ingredients_index_btn_text_fr") }}</span>
                            </a>
                            <a href="{{ route('ingredients.liste', ['latin']) }}" class="btn btn-large">
                                <span class="btn-text">{{ __("textes.ingredients_index_btn_text_la") }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
