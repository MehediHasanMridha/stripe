@extends('layouts.template')
@section('title'){{__("textes.conseillers_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
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
            <div class="row mb-5">
                <div class="col">
                    <div class="d-flex align-items-center justify-content-between">
                        <!--Affichage de "Recherche d'un praticien - Code"-->
                        <h2 class="mb-4">{{ __("textes.conseillers_code_search") }}</h2>

                        <!--Affichage de la croix pour revenir en arriere-->
                        <a href="{{ route('conseillers.index') }}">
                            <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <!--Formulaire de recherche de practitien par le code-->
                    <form action="{{ route('conseillers.codeSent') }}" method="POST">
                        @csrf
                        <row class="row row-conseillers-code g-3 align-items-center">
                            <div class="col-auto">
                                <h4><label for="code">{{ __("textes.conseillers_code_selectcode") }} <span
                                            style="color: red; vertical-align: top;">*</span></label></h4>
                            </div>
                            <div class="col-auto">
                                <!--Sélection du code du practitien-->
                                <select name="code" id="code" class="form-select @error('code') is-invalid @enderror">
                                    <option value="" hidden selected>{{__("textes.conseillers_code_code")}}</option>
                                    @foreach($codes as $code)
                                        <option value="{{ $code->id }}">{{ $code->code }}</option>
                                    @endforeach
                                </select>

                                <!--Affichage en cas d'erreur-->
                                @error('code')
                                <div class="invalid-feedback">
                                    {{ $errors->first('code') }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-auto">
                                <!--Boutons de validation-->
                                <button class="btn btn-small-plus" type="submit">
                                    <span class="btn-text px-4">{{__("textes.conseillers_code_btn_valider")}}</span>
                                </button>
                            </div>
                        </row>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
