@extends('layouts.template')
@section('title'){{__("textes.stripe_page_nom")}}@endsection
@section('content')
    <section class="section">

        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_stripe") }}</li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="text-center">{{ __("textes.stripe_success_titre") }}</h1>
                    <div class="d-flex align-items-center flex-column">
                        <p class="w-75">{{ __("textes.stripe_success_description") }}</p>
                        <a class="btn btn-large my-4 btn-size text-white"
                           href="{{route("compte.index")}}">{{ __("textes.stripe_success_btn_compte") }}</a>
                        <a class="btn btn-large my-4 btn-size text-white"
                           href="{{route("accueil.index")}}">{{ __("textes.stripe_success_btn_compte_home") }}</a>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
