@extends('layouts.template')
@section('title'){{__("textes.conseillers_page_nom")}}@endsection
@push('script')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    @isset($praticien->coordonnees->latitude)
        @isset($praticien->coordonnees->longitude)
            <script>
                let map;

                // Initialisation de la map
                function initMap() {
                    // La localisation de la France
                    const coordonnees = {
                        lat: {{ $praticien->coordonnees->latitude }},
                        lng: {{ $praticien->coordonnees->longitude }}
                    };
                    const zoom = 16;

                    // La map se centre sur la France
                    map = new google.maps.Map(document.getElementById("mapShow"), {
                        zoom: zoom,
                        center: coordonnees,
                        minZoom: 5.7,
                    });

                    const marker = new google.maps.Marker({
                        position: coordonnees,
                        map: map,
                    });
                }
            </script>
        @endisset
    @endisset
@endpush
@section('content')
    <section class="section">
        <div class="container">
            <div class="container">
                <!--Lien d'arianne ou Breadcrump-->
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="arianne-link"
                                                       href="/">{{ __("textes.arianne_accueil") }}</a></li>
                        <li class="breadcrumb-item"><a class="arianne-link"
                                                       href="/compte">{{ __("textes.arianne_compte") }}</a></li>
                        <li class="breadcrumb-item"><a class="arianne-link"
                                                       href="/compte/conseillers">{{ __("textes.arianne_fiche_conseiller") }}</a>
                        </li>
                        <li class="breadcrumb-item active"
                            aria-current="page">{{ __("textes.arianne_conseiller_afficher") }}</li>
                    </ol>
                </nav>
            </div>

            <div class="container">

                <div class="row">
                    <div class="col phone-margin">
                        <div class="d-flex align-items-center justify-content-between">
                            <!-- Nom du praticien -->
                            <h2 class="title-size">{{__("textes.conseillers_show_titre")}} {{ $praticien->nom }} </h2>

                            <!-- Affiche une îcone qui ramène l'utilisateur en arrière -->
                            <a href="{{route('compte.index')}}" class="btn-close ms-2 x-size"
                                    aria-label="Close"></a>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-center flex-wrap">
                    <a href="{{ route('compte.conseillers.edit') }}"
                       class="btn btn-admin btn-color text-white fw-bold mx-2 ">{{__("textes.conseillers_show_btn_edit")}}</a>
                    <form action="{{ route('compte.conseillers.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Êtes-vous sûr ?')" type="submit"
                                class="btn btn-admin btn-color text-white fw-bold">{{__("textes.conseillers_show_btn_supp")}}</button>
                    </form>
                </div>

                <div class="row row-conseillers-show mt-5">
                    <div class="col-xxl-5 col text-size phone-margin">

                        <!---------- ADRESSE ---------->
                        <div class="d-flex align-items-center">
                            <h3><i class="fas fa-map-marker-alt me-4"></i>{{__("textes.conseillers_show_adresse")}}</h3>
                        </div>

                        <p>{{ $praticien->adresse }}<br>{{ $praticien->CP }} {{ $praticien->ville }}</p>

                    @if($praticien->adresse2 != null)
                        <!---------- ADRESSE 2---------->
                            <div class="d-flex align-items-center">
                                <h3><i class="fas fa-map-marker-alt me-4"></i>{{__("textes.conseillers_show_adresse2")}}
                                </h3>
                            </div>

                            <p>{{ $praticien->adresse2 }}<br>{{ $praticien->CP2 }} {{ $praticien->ville2 }}</p>
                    @endif

                    <!---------- CONTACT ---------->
                        <div class="d-flex align-items-center mt-5">
                            <h3><i class="fas fa-phone-alt me-4"></i>{{__("textes.conseillers_show_contact")}}</h3>
                        </div>

                        <p>{{ $praticien->mobile }}<br>{{ $praticien->email }}</p>

                        <!---------- HORAIRES ---------->
                        <div class="d-flex align-items-center mt-5">
                            <h3><i class="fas fa-calendar-alt me-4"></i>{{__("textes.conseillers_show_horaires")}}</h3>
                        </div>

                        <!-- Affichage des horraires -->
                        <div>
                            @if (!empty($praticien->horaires))
                                <h5>{!! html_entity_decode($praticien->horaires, ENT_HTML5, 'UTF-8') !!}</h5>

                            @else
                                <em class="fs-5 text-muted">{{__("textes.conseillers_show_horaires_none")}}</em>
                            @endif
                        </div>
                    </div>
                </div>

                <!---------- MAP ---------->
                <div class="col-xxl-7 col colMap">
                    <div class="mx-auto" id="mapShow"></div>
                </div>
            </div>
        </div>

        <!-- Script pour la map -->
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD982868RehTHPFdeV_g2tRmoL3f4IYPOs&callback=initMap&libraries=&v=weekly"
            async
        ></script>
    </section>
@endsection
