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
                    <li class="breadcrumb-item active" aria-current="page">
                        <a class="arianne-link" href="{{ route('conseillers.index') }}">{{ __("textes.arianne_recherche_conseillers") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_liste") }}</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col phone-margin">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="title-size">{{__("textes.conseillers_liste_search")}}</h2>
                        <a href="{{ route('conseillers.listeForm') }}">
                            <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                        </a>
                    </div>

                    <h3 class="mt-5">{{__("textes.conseillers_liste_result")}} {{ $zone_searched }}</h3>

                    <!-- Si des praticiens ont été trouvé dans la zone demandé -->
                    @if(!empty($zones))
                        @foreach($zones as $zone => $praticiens)
                            <div class="my-5">
                                <h4 class="mb-4">{{ $zone }}</h4>

                                @foreach($praticiens as $praticien)
                                    <div class="phone-margin d-flex align-items-center">
                                        <!-- On affiche un cercle avec les initiales du praticien -->
                                        <div class="circle me-5">
                                            <strong>{{ substr(explode(' ', $praticien->nom)[0], 0, 1) . ' ' . substr(explode(' ', $praticien->nom)[1], 0, 1) }}</strong>
                                        </div>


                                        <div class="flex-column">
                                            <!-- On affiche le nom du praticiens qui amène l'utilisateur vers la page de ce praticien -->
                                            <a href="{{ route('conseillers.show', [$praticien->id]) }}"
                                               class="fs-3 text-decoration-none">
                                                <h4 class="list-element-name"> {{ $praticien->nom }} </h4>
                                            </a>

                                            <!-- On affiche l'adresse du praticien -->
                                            <p class="list-element-desc">
                                                {{ $praticien->adresse }}, {{ $praticien->CP }}, {{ $praticien->ville }}
                                            </p>
                                        </div>

                                    </div>
                                    <!-- Ligne de séparation entre chaque praticien -->
                                    <hr class="list-element-separation">
                                @endforeach
                            </div>
                        @endforeach

                    <!-- Si aucun praticien n'a été trouvé dans la zone demandé -->
                    @else
                        <div>
                            <!-- On affiche un message expliquant qu'aucun praticien n'a été trouvé -->
                            <em class="fs-5">{{ __("textes.conseillers_liste_search_aucun") }}</em>
                        </div>
                @endif
                </div>
            </div>
        </div>
    </section>
@endsection
