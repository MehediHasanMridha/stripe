@extends('layouts.template')
@section('title'){{__("textes.expertise_page_nom")}}@endsection
@push('script')
    <script src='https://cdn.plot.ly/plotly-2.9.0.min.js'></script>
@endpush
@section('content')
    <section class="no-space-top">

        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a class="arianne-link"
                                                                              href="{{ route('expertise.index') }}">{{ __("textes.arianne_expertise") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a class="arianne-link"
                                                                              href="{{ route('expertise.symptome') }}">{{ __("textes.arianne_symptomes") }}</a>
                    </li>
                    <li class="breadcrumb-item">{{ __("textes.arianne_resultat") }}</li>
                </ol>
            </nav>
        </div>

        <div class="container mt-2">

            <div class="d-flex align-items-center justify-content-between mb-3">

                <div></div>

                <h2 class="my-3 phone-padding">{{__("textes.expertise_show_titre")}}</h2>

                <button type="button" onclick="window.history.go(-1)"
                        class="btn-close ms-2 title-show-size show-margin-right x-size" aria-label="Close"></button>
            </div>
            <div class="container phone-margin rappels-juridiques">
                <li><strong> {{__("textes.expertise_but_informatif")}} </strong></li>
                <li><strong> {{__("textes.expertise_index_modal_juridique")}} </strong></li>
                <ul>
                    <li>{{__("textes.expertise_index_modal_personnes")}}
                        <strong> {{__("textes.expertise_index_modal_consultants")}} </strong> {{__("textes.expertise_index_modal_personnes2")}}
                    </li>
                    <li>{{__("textes.expertise_index_modal_pharmacie")}} </li>
                    <li>{{__("textes.expertise_index_modal_prescription")}}</li>
                    <li>{{__("textes.expertise_index_modal_medicaments")}} </li>
                </ul>
            </div>
            <!-- div qui affiche les symptômes que l'utilisateur à sélectionnés -->
            <div>
                <ul class="list-symptomes-group fs-3">
                    @foreach($liste_symptomes as $symptome)
                        @if($symptome != "")
                            <li class="list-symptomes-item border border-light shadow-sm p-2 mb-1 bg-body rounded symptome-user ">{{ $symptome }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <div class="row row-show mb-5">
                <div class="col col-marge border-separator">

                    <!-----------------
                    - FORMULES
                    ------------------->

                    <h3 class="text-center text-show-size">{{__("textes.expertise_show_resultat_symptomes")}}</h3>

                @if (!empty($formules))
                    <!-- Diagramme qui affiche la probabilité de chaque formule avec le(s) symptôme(s) sélectionné(s) -->
                        <div class="d-flex w-100 align-items-center justify-content-center my-4">
                            <div id="diagram-formules"></div>
                        </div>

                        <!-- Bouttons qui affichent les 3 formules qui ont le meilleurs score avec le(s) symptôme(s) sélectionné(s) -->
                        <div class="d-flex flex-column">
                            @foreach($formules as $idx => $formule)
                                @if($formule->nom)
                                    <button
                                        onclick="window.location.href='{{ route('formules.show', ['fr', $formule->id]) }}'"
                                        style="background-color: {{ $idx == 0 ? '#ff8c8c' : ''}} {{ $idx == 1 ? '#92647c' : '' }} {{ $idx == 2 ? '#F93d3d' : '' }};"
                                        class="btn my-2 mx-auto text-center text-white  text-show-size w-50 rounded"
                                    >
                                        {{($idx+1).". ".$formule->nom_chinois }} <span class="text-end">({{ $proba_formules[$idx] }}%)</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <em class="text-muted">{{ __("textes.expertise_show_resultat_no_formules") }}</em>
                    @endif
                </div>

                <div class="col col-marge">

                    <!-----------------
                    - SYNDROMES
                    ------------------->

                    <h3 class="text-center  text-show-size">{{__("textes.expertise_show_resultat_syndromes")}}</h3>

                @if (!empty($syndromes))
                    <!-- Diagramme qui affiche la probabilité de chaque syndromes avec le(s) symptôme(s) sélectionné(s) -->
                        <div class="d-flex w-100 align-items-center justify-content-center my-4">
                            <div id="diagram-syndromes"></div>
                        </div>

                        <!-- Bouttons qui affichent les 3 syndromes qui ont le meilleurs score avec le(s) symptôme(s) sélectionné(s) -->
                        <div class="d-flex flex-column">
                            @foreach($syndromes as $idx => $syndrome)
                                @if($syndrome->nom)
                                    <button
                                        onclick="window.location.href='{{ route('syndromes.show', [$syndrome->id]) }}'"
                                        style="background-color: {{ $idx == 0 ? '#f1c50e' : ''}} {{ $idx == 1 ? '#349bdb' : '' }} {{ $idx == 2 ? '#1fd027' : '' }};"
                                        class="btn my-2 mx-auto text-center text-white  text-show-size w-50 rounded"
                                    >
                                        {{ $syndrome->nom }} <span
                                            class="text-end">({{ $proba_syndromes[$idx] }}%)</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <em class="text-muted">{{ __("textes.expertise_show_resultat_no_syndromes") }}</em>
                    @endif
                </div>
            </div>
        </div>
        <div class="container phone-margin rappels-juridiques">
            <p><strong>{{__("textes.expertise_titre_seance")}} </strong></p>
            <ol>
                <li>{{__("textes.expertise_etape1")}}</li>
                <li>{{__("textes.expertise_etape2")}}</li>
                <li>{{__("textes.expertise_etape3")}}</li>
                <li>{{__("textes.expertise_etape4")}}</li>
                <li>{{__("textes.expertise_etape5")}}</li>
            </ol>
            <div class="d-flex justify-content-center">
                <a class="btn btn-large my-4" href="{{ route('conseillers.index') }}">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-user-md icon-white pe-3"></i>
                        <span class="btn-text">{{__("textes.expertise_trouver_conseiller")}} </span>
                    </div>
                </a>
            </div>
        </div>
        <!-- div pour que le footer ne déborde pas sur le dernier élément -->
    </section>

    <script>
        const colors = [
            '#ff8c8c',
            '#92647c',
            '#F93d3d',
            '#1fd027',
            '#349bdb',
            '#f1c50e',
        ]
        let dataForm = [{
            type: "pie",
            values: [
                @foreach($proba_formules as $proba)
                    {{$proba}},
                @endforeach
            ],
            labels: [
                @foreach($formules as $id=>$formule)
                    '{{$id+1}}',
                @endforeach
            ],
            textinfo: "label",
            insidetextorientation: "horizontal",
            hoverinfo: 'percent',
            marker: {
                colors: colors
            },
        }]
        let dataSynd = [{
            type: "pie",
            values: [
                @foreach($proba_syndromes as $proba)
                    {{$proba}},
                @endforeach
            ],
            textinfo: 'none',
            hoverinfo: 'percent',
            marker: {
                colors: [...colors].reverse()
            },
        }]

        let layout = {
            height: 450,
            width: 500,
            showlegend: false,
        }
        Plotly.newPlot('diagram-formules', dataForm, layout)
        Plotly.newPlot('diagram-syndromes', dataSynd, layout)
    </script>
@endsection

