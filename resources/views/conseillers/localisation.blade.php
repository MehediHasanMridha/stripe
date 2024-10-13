@extends('layouts.template')
@section('title'){{__("textes.conseillers_page_nom")}}@endsection
@push('script')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script>
        let map;

        // Initialisation de la map
        function initMap() {
            // La localisation de la France
            const coordonnees = {lat: {{ $coordonnees[0] }}, lng: {{ $coordonnees[1] }}};
            const zoom = {{ $zoom }};

            // La map se centre sur la France
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: zoom,
                center: coordonnees,
                minZoom: 5.7,
            });

            const markers = @json($markers);

            for (let i = 0; i < markers.length; i++) {
                const nouveauMarker = [];
                for (const [key, value] of Object.entries(markers[i])) {
                    nouveauMarker.push(value);
                }
                addMarker(nouveauMarker[0], nouveauMarker[1], nouveauMarker[2]['latitude'], nouveauMarker[2]['longitude'])
            }
        }

        // Ajoute un marker sur la map à la position donnée
        function addMarker(id, title, latitude, longitude) {
            const marker = new google.maps.Marker({
                position: new google.maps.LatLng(latitude, longitude),
                title: 'M.' + String(title),
                map: map
            });

            marker.addListener("click", () => {
                goToPraticienDetail(id)
            });
        }

        // Lorsque l'utilisateur clique sur le bouton "me géolocaliser"
        function initMyPosition() {
            // Si le navigateur supporte la géolocalisation
            if (navigator.geolocation)
                navigator.geolocation.watchPosition(
                    successCallback,
                    null,
                    {enableHighAccuracy: true}
                );
            // Sinon on affiche une alerte comme erreur
            else
                alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");

            // Si la géolocalisation a été un succès
            function successCallback(position) {
                map.panTo(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
                map.setZoom(11);
                const marker = new google.maps.Marker({
                    position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
                    icon: {
                        url: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
                    },
                    map: map
                });
            }
        }

        function goToPraticienDetail(id_praticien) {
            document.location.href = "/conseillers/" + id_praticien;
        }
    </script>
@endpush
@section('content')
    <section class="section">
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a class="arianne-link"
                                                                              href="{{ route('conseillers.index') }}">{{ __("textes.arianne_conseillers") }}</a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">{{ __("textes.arianne_recherche_conseillers") }}</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col phone-margin">
                    <div class="d-flex align-items-end justify-content-between">
                        <h2 class="title-size">{{__("textes.conseillers_localisation_titre")}}</h2>

                        <!-- Affiche une îcone qui ramène l'utilisateur vers la page des options pour trouver un praticien -->
                        <a href="{{ route('conseillers.index') }}">
                            <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row my-4">
                <div class="col">
                    <div class="mx-auto" id="map"></div>
                </div>
            </div>

            <div class="row row-conseillers-localisation">
                <div class="col">
                    <div class="d-flex justify-content-start">
                        <button class="btn btn-medium mb-4" onclick="initMyPosition()">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-map-marker-alt icon-white pe-3"></i> <span
                                    class="btn-text"> {{__("textes.conseillers_localisation_geolocalisation")}} </span>
                                <div></div>
                            </div>
                        </button>
                    </div>
                </div>

                <div class="col">
                    <form action="{{ route('conseillers.localisationCP') }}" method="GET" class="row g-2">

                        <div class="col-auto">
                            <label for="codePostal"
                                   class="visually-hidden">{{__("textes.conseillers_localisation_placeholder_code_postal")}}</label>
                            <input type="number"
                                   class="form-control list-search py-4 @error('codePostal') is-invalid @enderror"
                                   id="codePostal" name="codePostal"
                                   placeholder="{{__("textes.conseillers_localisation_placeholder_code_postal")}}">

                            @error('codePostal')
                            <div class="invalid-feedback">
                                {{ $errors->first('codePostal') }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-auto">
                            <button type="submit" class="btn btn-small mb-3 px-3 text-white">
                                <span class="btn-text">{{__("textes.conseillers_localisation-valider")}}</span>
                            </button>

                            <!-- Si l'utilisateur a effectué une recherche -->
                        @if(request()->input('codePostal'))
                            <!-- Affiche une îcone qui ramène l'utilisateur vers la page qui affiche entièrement la map -->
                                <a href="{{ route('conseillers.localisation') }}">
                                    <button type="button" class="btn-close ms-1 icon-size pt-2"
                                            aria-label="Close"></button>
                                </a>
                            @endif
                        </div>
                    </form>
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
