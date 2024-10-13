@extends('layouts.template')
@section('title'){{__("textes.formules_page_nom")}}@endsection
@push('script')
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.css">
    <script src="https://unpkg.com/bootstrap-table@1.19.1/dist/bootstrap-table.min.js"></script>
@endpush
@section('content')
    <section>
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a class="arianne-link"
                                                                              href="{{ route('formules.index') }}">{{ __("textes.arianne_formules") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a class="arianne-link"
                                                                              href="{{ route('formules.liste', ['cn']) }}">{{ __("textes.arianne_liste") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $formule->nom_chinois }}</li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <!-- Si des données sur la formule existe -->
        @if(!empty($formule))
            <!-- Pour chaque données récupéré de la formule et contenu dans donnees_formule -->
                <div class="mb-5">
                    <!-- div qui permet d'afficher les éléments sur la même ligne mais espacés -->
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Affiche dans un titre de niveau 3 le nom générique de la formule -->
                        <h3 class="show-margin-left"></h3>
                        <!-- Affiche dans un titre de niveau 2 le nom chinois de la formule -->
                        <h2 class="text-center my-2">{{ $formule->nom_chinois }}</h2>
                        <!-- Affiche une icone qui ramène l'utilisateur en arrière -->
                        <button type="button" onclick="window.history.go(-1)"
                                class="btn-close ms-2 x-size show-margin-right" aria-label="Close"></button>
                    </div>

                    <!-- Affiche dans un titre de niveau 3 le nom français de la formule -->
                    <h3 class="text-center text-muted my-3">({{ $formule->nom_langue }})</h3>

                </div>

                <div class="row row-show">
                    <div class="col col-left my-4">
                        <div>
                            <!-- affiche une image -->


                            @if (!empty($formule->image))
                                <img src="{{asset($formule->image)}}" class="img-thumbnail thumbnail-width"
                                     alt="{{__("textes.ingredientsAdmin_show_image_alt")}}"/>
                            @else
                                <img
                                    src="{{ asset('img/empty.png') }}"
                                    alt="Image d'un flacon de {{ $formule->nom }}"
                                    class="img-thumbnail image-show-size"/>
                            @endif
                        </div>


                    </div>

                    <div class="col my-4">
                        <div class="d-flex justify-content-start ">
                            <!-- div contennant tous les accordéons -->
                            <div class="accordion w-lg-75 w-100" id="accordionExample">

                                <!-----------------------
                                - Accordéon Ingrédient
                                ------------------------>


                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseOne"
                                                    aria-expanded="true" aria-controls="collapseOne">
                                                <i class="fas fa-leaf me-2"></i>{{ __("textes.formules_show_accordeon_ingredients") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                         data-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <!-- Tableau contenant tous les ingrédients et sa quantité lié à cette formule -->
                                            <table data-toggle="table" class="table w-100" data-sort-name="ponderation" data-sort-order="desc">
                                                <thead>
                                                    <tr class="border-bottom">
                                                        <th class="w-75 cursor-pointer" data-sortable="true" data-field="nom">
                                                            <strong>{{ __("textes.formules_show_accordeon_ingredients") }}</strong>
                                                        </th>
                                                        <th class="cursor-pointer" data-sortable="true" data-field="ponderation" data-sorter="ponderationSort">
                                                            <strong>{{ __("textes.formules_show_accordeon_ingredients_ponderation") }}</strong>
                                                        </th>
                                                        <th class="cursor-pointer" data-sortable="true" data-field="quantite">
                                                            <strong>{{ __("textes.formules_show_accordeon_ingredients_quantite") }}</strong>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <!-- Pour chaque ingrédient récupéré du tableau ingrédients et contenu dans ingrédient -->
                                                <tbody id="trier" class="align-middle">
                                                @foreach($ingredients as $ingredient)
                                                    <tr>
                                                        <!-- On affiche le nom de l'ingrédient qui si cliqué amène l'utilisateur vers cette ingrédient -->
                                                        <td class="my-1" field="nom">
                                                            <a href="{{ route('ingredients.show', [ 'cn', $ingredient->id]) }}"
                                                               class="show-element-name"> {{ $ingredient->nom_chinois }} </a><br>
                                                            <a href="{{ route('ingredients.show', [ $selectedLang, $ingredient->id]) }}"
                                                               class="show-element-secondary"> {{ $ingredient->nom }} </a><br>
                                                            <a href="{{ route('ingredients.show', [ 'latin', $ingredient->id]) }}"
                                                               class="show-element-secondary fst-italic"> {{ $ingredient->nom_latin }} </a>
                                                        </td>
                                                        <!-- Affiche la quantité de l'ingrédient (pour l'instant, donnée inexistante) -->
                                                        <td data-id="{{$ingredient->ponderation_id}}" field="ponderation"> {{ $ingredient->ponderation }}</td>
                                                        <td field="quantite"> {{ $ingredient->quantite }}@if($ingredient->quantite)
                                                                %@endif</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                                <!-----------------------
                                - Accordéon Intérêts
                                ------------------------>


                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingTwo">
                                        <h2 class="mb-0">
                                            <button
                                                class="btn accordion-button @if(!request('Symptomesearch')) collapsed @endif"
                                                type="button" data-toggle="collapse" data-target="#collapseTwo"
                                                aria-expanded="false" aria-controls="collapseTwo">
                                                <i class="fas fa-stethoscope me-2"></i>{{ __("textes.formules_show_accordeon_interets") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseTwo" class="collapse @if(request('Symptomesearch')) show @endif"
                                         aria-labelledby="headingTwo" data-parent="#accordionExample">
                                        <div class="accordion-body">

                                            <div>
                                                <form
                                                    action="{{ route('formules.symptomeSearch', [ $selectedLang, $formule->id]) }}"
                                                    class="phone-margin d-flex" method="GET">

                                                    <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui être transmis par la suite au code -->
                                                <!-- <input class="form-control me-2 list-search" type="text" name="Symptomesearch" placeholder="Rechercher un symptôme..." @if(request('Symptomesearch')) value="{{ request('Symptomesearch') }}" @endif autocomplete="off" aria-label="Search">-->
                                                    <!-- Bouton affichant une icone et permettant de valider la recherche -->
                                                    <!-- <button class="btn btn-small"><i class="fas fa-search icon-size icon-white"></i></button>-->

                                                    <!-- Si l'utilisateur a effectué une recherche -->
                                                @if(request('Symptomesearch'))
                                                    <!-- Affiche une icone qui ramène l'utilisateur vers la page contenant la liste de tous les ingrédients -->
                                                        <a href="{{ route('formules.show', [ $selectedLang, $formule->id]) }}">
                                                            <button type="button" class="btn-close ms-2 x-size"
                                                                    aria-label="Close"></button>
                                                        </a>
                                                    @endif

                                                </form>
                                            </div>

                                            <!-- Tableau contenant tous les symptômes et son score lié à cette formule -->
                                            <table class="w-100 ">
                                                <tr class="border-bottom">
                                                    <td>
                                                        <strong>{{ __("textes.formules_show_accordeon_interets_symptomes") }}</strong>
                                                    </td>
                                                </tr>
                                                <!-- Pour chaque symptome récupéré du tableau symptomes et contenu dans symptome -->
                                                @foreach($symptomes as $symptome)
                                                    <tr class="my-1">
                                                        <!-- Affiche le nom du symptôme -->
                                                        <td class="w-100"> {{ $symptome->traduction->text }} </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>


                                <!-----------------------
                                - Accordéon Conseil
                                ------------------------>


                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingThree">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseThree"
                                                    aria-expanded="false" aria-controls="collapseThree">
                                                <i class="fas fa-comment-medical me-2"></i>{{ __("textes.formules_show_accordeon_conseil") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                         data-parent="#accordionExample">
                                        <div class="accordion-body text-justify margin-navbar">
                                            {!! html_entity_decode($formule->conseil, ENT_HTML5, 'UTF-8') !!}
                                        </div>
                                    </div>
                                </div>


                                <!-----------------------
                                - Accordéon pharmacologie
                                ------------------------>


                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingFour">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseFour"
                                                    aria-expanded="false" aria-controls="collapseFour">
                                                <i class="fas fa-capsules me-2"></i>{{ __("textes.formules_show_accordeon_pharmacologie") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                                         data-parent="#accordionExample">
                                        <div class="accordion-body text-justify margin-navbar">
                                            {!! html_entity_decode($formule->pharmacologie, ENT_HTML5, 'UTF-8') !!}
                                        </div>
                                    </div>
                                </div>


                                <!-----------------------
                                - Accordéon toxicologie
                                ------------------------>


                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingFive">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseFive"
                                                    aria-expanded="false" aria-controls="collapseFive">
                                                <i class="fas fa-biohazard me-2"></i>{{__("textes.formules_show_accordeon_toxicologie") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive"
                                         data-parent="#accordionExample">
                                        <div class="accordion-body text-justify margin-navbar">
                                            {!! html_entity_decode($formule->toxicologie, ENT_HTML5, 'UTF-8') !!}
                                        </div>
                                    </div>
                                </div>


                                <!-----------------------
                                - Accordéon actions
                                ------------------------>


                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingSix">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseSix"
                                                    aria-expanded="false" aria-controls="collapseSix">
                                                <i class="fas fa-exclamation-circle me-2"></i>{{ __("textes.formules_show_accordeon_actions") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix"
                                         data-parent="#accordionExample">
                                        <div class="accordion-body text-justify margin-navbar">
                                            {!! html_entity_decode($formule->actions, ENT_HTML5, 'UTF-8') !!}
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
                    <!-- Affiche une icone qui ramène l'utilisateur à la page contenant la liste de toutes les formules -->
                    <a href="{{ route('formules.liste', [ $selectedlang]) }}" class="mx-3">
                        <i class="fas fa-long-arrow-alt-left fs-2" style="color: black;"></i>
                    </a>
                    <!-- Texte annonçant qu'aucune donnée sur cette ingrédient trouvé n'a été trouvé -->
                    <em class="fs-5 ms-3">{{__("textes.formules_liste_search_aucun")}}</em>
                </div>
            @endif

        </div>
    </section>
    <style>
        .cursor-pointer {
            cursor: pointer;
            color: dodgerblue;
        }
    </style>

    <script>
        const ponderation={"Empereur":1,"Ministre":2,"Conseiller":3,"Ambassadeur":4};
        function ponderationSort(a,b){
            return ponderation[b]-ponderation[a];
        }
    </script>
@endsection


