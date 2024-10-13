@extends('layouts.template')
@section('title'){{__("textes.ingredients_page_nom")}}@endsection
@section('content')
    <section>
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a class="arianne-link" href="{{ route('ingredients.index') }}">{{ __("textes.arianne_ingredients") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a class="arianne-link" href="{{ route('ingredients.liste', ['cn']) }}">{{ __("textes.arianne_liste") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $ingredient->nom_langue }}</li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <!-- Si des données sur l'ingrédient existe -->
        @if(!empty($ingredient))
            <!-- Pour chaque données récupéré de l'ingrédient et contenu dans donnees_ingredient -->
                <div class="mb-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Élément permettant de centrer le texte et de placer le bouton à droite -->
                        <div class="show-margin-left"></div>

                        <!-- Affiche dans un titre de niveau 2 le nom de l'ingrédient -->
                        <h2 class="text-center my-2">{{ $ingredient->nom_langue }}</h2>

                        <!-- Affiche une îcone qui ramène l'utilisateur en arrière -->
                        <button type="button" onclick="window.history.go(-1)"
                                class="btn-close ms-2 show-margin-right x-size" aria-label="Close"></button>
                    </div>
                </div>

                <div class="row row-show">
                    <div class="col col-left my-4">
                        <div>
                            <!-- affiche une image -->
                            @if (!empty($ingredient->image))
                                <img src="{{ asset($ingredient->image) }}" class="img-thumbnail image-show-size"
                                     alt="{{ __("textes.ingredientsAdmin_show_image_alt") }}">
                            @else
                                <img src="{{ asset('img/empty.png') }}" class="img-thumbnail image-show-size"
                                     alt="{{ __("textes.ingredientsAdmin_show_image_alt") }}">
                            @endif
                        </div>
                    </div>

                    <div class="col my-4">
                        <div class="d-flex justify-content-start ">
                            <!-- div contennant tous les accordéons -->
                            <div class="accordion w-lg-75 w-100" id="accordionExample">

                                <!-----------------------
                                - Accordéon Actions
                                ------------------------>


                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed " type="button"
                                                    data-toggle="collapse" data-target="#collapseOne"
                                                    aria-expanded="true" aria-controls="collapseOne">
                                                <i class="fas fa-capsules me-2"></i> {{ __("textes.ingredients_show_accordeon_actions") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                         data-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <!-- Tableau contenant toutes les formules lié à cet ingrédient -->
                                            <table class="w-100">
                                                <tr class="border-bottom">
                                                    <td>
                                                        <strong>{{ __("textes.ingredients_show_accordeon_actions_formules") }}</strong>
                                                    </td>
                                                </tr>
                                                <!-- Pour chaque formule récupéré du tableau formules et contenu dans formule -->
                                                @foreach($formules as $formule)
                                                    <tr>
                                                        <!-- Pour chaque données récupéré de la formule et contenu dans donnees_formule -->
                                                        <td class="my-1">
                                                            <!-- Lien qui amène l'utilisateur vers la formule -->
                                                            <a href="{{route('formules.show', [$selectedLang, $formule->id])}}"
                                                               class="show-element-name">
                                                                <!-- Si la langue sélectionné est fr on affiche le nom français de la formule -->
                                                            @if($selectedLang == 'fr') {{ $formule->nom_langue }}

                                                            <!-- Sinon si la langue sélectionné est cn on affiche le nom chinois de la formule -->
                                                            @elseif($selectedLang == 'cn') {{ $formule->nom_chinois }}

                                                            <!-- Sinon on affiche le nom générique de la formule -->
                                                                @else {{ $formule->nom_langue }}

                                                                @endif
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>

                                </div>


                                <!-----------------------
                                - Accordéon Tropisme
                                ------------------------>


                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingTwo">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                <i class="fas fa-mountain me-2"></i> {{ __("textes.ingredients_show_accordeon_tropisme") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                         data-parent="#accordionExample">
                                        <div class="accordion-body  text-justify">
                                            @if (!empty($ingredient->tropisme))
                                                {!! html_entity_decode($ingredient->tropisme, ENT_HTML5, 'UTF-8') !!}
                                            @else
                                                <em class="fs-5 text-muted">{{ __("textes.ingredientsAdmin_show_tropisme_none") }}</em>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <!-----------------------
                                - Accordéon nature
                                ------------------------>

                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingThree">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseThree"
                                                    aria-expanded="false" aria-controls="collapseThree">
                                                <i class="fab fa-pagelines me-2"></i> {{ __("textes.ingredients_show_accordeon_nature") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                         data-parent="#accordionExample">
                                        <div class="accordion-body  text-justify">
                                            @if (!empty($ingredient->nature))
                                                {!! html_entity_decode($ingredient->nature, ENT_HTML5, 'UTF-8') !!}
                                            @else
                                                <em class="fs-5 text-muted">{{ __("textes.ingredientsAdmin_show_nature_none") }}</em>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-----------------------
                                - Accordéon saveur
                                ------------------------>

                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingFour">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseFour"
                                                    aria-expanded="false" aria-controls="collapseFour">
                                                <i class="fas fa-ellipsis-h me-2"></i> {{ __("textes.ingredients_show_accordeon_saveur") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                                         data-parent="#accordionExample">
                                        <div class="accordion-body  text-justify">
                                            @if (!empty($ingredient->saveur))
                                                {!! html_entity_decode($ingredient->saveur, ENT_HTML5, 'UTF-8') !!}
                                            @else
                                                <em class="fs-5 text-muted">{{ __("textes.ingredientsAdmin_show_saveur_none") }}</em>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <!-----------------------
                                - Accordéon action
                                ------------------------>

                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingFive">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseFive"
                                                    aria-expanded="false" aria-controls="collapseFive">
                                                <i class="fas fa-location-arrow me-2"></i> {{ __("textes.ingredients_show_accordeon_action") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive"
                                         data-parent="#accordionExample">
                                        <div class="accordion-body  text-justify">
                                            @if (!empty($ingredient->action))
                                                {!! html_entity_decode($ingredient->action, ENT_HTML5, 'UTF-8') !!}
                                            @else
                                                <em class="fs-5 text-muted">{{ __("textes.ingredientsAdmin_show_action_none") }}</em>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-----------------------
                                - Accordéon partie
                                ------------------------>

                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingSix">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseSix"
                                                    aria-expanded="false" aria-controls="collapseSix">
                                                <i class="fas fa-map-marker me-2"></i> {{ __("textes.ingredients_show_accordeon_partie") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix"
                                         data-parent="#accordionExample">
                                        <div class="accordion-body  text-justify">
                                            @if (!empty($ingredient->partie))
                                                {!! html_entity_decode($ingredient->partie, ENT_HTML5, 'UTF-8') !!}
                                            @else
                                                <em class="fs-5 text-muted">{{ __("textes.ingredientsAdmin_show_partie_none") }}</em>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Si des données sur l'ingrédient n'existe pas -->
        @else
            <div class="my-2 d-flex align-items-center">
                <!-- Affiche une îcone qui ramène l'utilisateur à la page contenant la liste de tous les ingrédients -->
                <a href="{{ route('ingredients.liste', [$selectedLang]) }}" class="mx-3">
                    <i class="fas fa-long-arrow-alt-left fs-2" style="color: black;"></i>
                </a>
                <!-- Texte annonçant qu'aucune donnée sur cet ingrédient trouvé n'a été trouvé -->
                <em class="fs-5 ms-3">{{__("textes.ingredients_show_search_aucun")}}</em>
            </div>
    @endif
    </section>
@endsection
