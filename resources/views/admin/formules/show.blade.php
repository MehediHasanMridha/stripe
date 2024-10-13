@extends('layouts.admin-template')
@section('title'){{__("textes.formulesAdmin_page_nom")}}@endsection
@section('content')
    <section>
        <div class="container-fluid container-xl">
            <!-- Si des données sur le syndrome existe -->
            @if(!empty($formule))

                <div class="mb-5">
                    <!-- div qui permet d'afficher les éléments sur la même ligne mais espacés -->
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Affiche dans un titre de niveau 3 le nom générique de la formule -->
                        <h3 class="show-margin-left">{{ $formule->code }}</h3>
                        <!-- Affiche dans un titre de niveau 2 le nom chinois de la formule -->
                        <h2 class="text-center my-2">{{ $formule->nom_chinois }}</h2>
                        <!-- Affiche une icone qui ramène l'utilisateur en arrière -->
                        <button type="button" onclick="window.history.go(-1)"
                                class="btn-close ms-2 x-size show-margin-right" aria-label="Close"></button>
                    </div>

                    <!-- Affiche dans un titre de niveau 3 le nom français de la formule -->
                    <h3 class="text-center text-muted my-3">({{ $formule->nom }})</h3>

                </div>

                <div class="container-fluid">
                    <div class="row">

                        <div class="d-flex align-items-center">

                            <a href="{{ route('formulesAdmin.edit', [$formule->id]) }}"
                               class="btn btn-admin btn-color text-white fw-bold me-4">{{__("textes.formulesAdmin_show_btn_edit")}}</a>
                        <!--<a href="{{ route('formules.traduction', [$formule->id]) }}" class="btn btn-admin btn-color text-white fw-bold me-4">{{__("textes.formulesAdmin_show_btn_traduction")}}</a>-->
                            <form action="{{ route('formulesAdmin.destroy', [$formule->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Êtes-vous sûr ?')"
                                        class="btn btn-admin btn-color text-white me-4 fw-bold">{{__("textes.formulesAdmin_show_btn_supp")}}</button>
                            </form>

                        </div>


                        <div class="col my-4">
                            <!-- Affichage de l'image -->
                            <div>
                                <h4>{{__("textes.ingredientsAdmin_show_image")}}</h4>
                                @if (!empty($formule->image))
                                    <img src="{{asset($formule->image)}}" class="img-thumbnail thumbnail-width"
                                         alt="{{__("textes.ingredientsAdmin_show_image_alt")}}">
                                @else
                                    <em class="fs-5 text-muted">{{__("textes.ingredientsAdmin_show_image_none")}}</em>
                                @endif
                            </div>

                            <!-- Affichage des conseils -->
                            <div>
                                <h4>{{ __("textes.formules_show_accordeon_conseil") }}</h4>
                                @if (!empty($formule->conseil))
                                    <h5>{!! html_entity_decode($formule->conseil, ENT_HTML5, 'UTF-8') !!}</h5>
                                @else
                                    <em class="fs-5 text-muted">{{ __("textes.formules_show_accordeon_conseil_none") }}</em>
                                @endif
                            </div>

                            <!-- Affichage de la pharmacologie -->
                            <div>
                                <h4>{{ __("textes.formules_show_accordeon_pharmacologie") }}</h4>
                                @if (!empty($formule->pharmacologie))
                                    <h5>{!! html_entity_decode($formule->pharmacologie, ENT_HTML5, 'UTF-8') !!}</h5>
                                @else
                                    <em class="fs-5 text-muted">{{ __("textes.formules_show_accordeon_pharmacologie_none") }}</em>
                                @endif
                            </div>

                            <!-- Affichage de la toxicologie -->
                            <div>
                                <h4>{{__("textes.formules_show_accordeon_toxicologie") }}</h4>
                                @if (!empty($formule->toxicologie))
                                    <h5>{!! html_entity_decode($formule->toxicologie, ENT_HTML5, 'UTF-8') !!}</h5>
                                @else
                                    <em class="fs-5 text-muted">{{__("textes.formules_show_accordeon_toxicologie_none") }}</em>
                                @endif
                            </div>

                            <!-- Affichage de la actions -->
                            <div>
                                <h4>{{ __("textes.formules_show_accordeon_actions") }}</h4>
                                @if (!empty($formule->actions))
                                    <h5>{!! html_entity_decode($formule->actions, ENT_HTML5, 'UTF-8') !!}</h5>
                                @else
                                    <em class="fs-5 text-muted">{{ __("textes.formules_show_accordeon_actions_none") }}</em>
                                @endif
                            </div>

                            <!-- div contennant tous les accordéons -->
                            <div class="accordion w-100" id="accordionExample">


                                <!-- Accordéon symptomes -->
                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingFour">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseFour"
                                                    aria-expanded="true" aria-controls="collapseFour">
                                                {{ __("textes.formules_show_accordeon_symptomes") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour">
                                        <div class="accordion-body">


                                            <!-- Tableau contenant toutes les symptomes lié à ce syndrome -->
                                            <table class="w-100">
                                                <tr class="border-bottom">
                                                    <td>
                                                        <strong>{{ __("textes.formules_show_accordeon_symptomes_colone_1") }}</strong>
                                                    </td>
                                                    <td>
                                                        <strong>{{ __("textes.formules_show_accordeon_symptomes_colone_2") }}</strong>
                                                    </td>
                                                </tr>
                                            @if(!empty($symptomes))
                                                <!-- Pour chaque formule récupéré du tableau formules et contenu dans formule -->
                                                    @foreach($symptomes as $symptome)
                                                        <tr class="table-line">
                                                            <!-- Pour chaque données récupéré de la formule et contenu dans donnees_formule -->
                                                            <td class="my-1 w-25">
                                                                <!-- Lien qui amène l'utilisateur vers la formule -->
                                                                <a href="{{ route('symptomes.show', [$symptome->id]) }}"
                                                                   class="text-decoration-none"> {{ $symptome->traduction->text }} </a>
                                                            </td>
                                                            <!-- Affiche le score du syndrome -->
                                                            <td class="w-25"> {{ $symptome->score }} </td>
                                                        </tr>
                                                    @endforeach

                                                @else
                                                <!-- si aucune formules n'as été trouvée -->
                                                    <tr class="table-line">
                                                        <td class="my-1 w-25">{{__("textes.symptomes_show_search_aucun")}}</td>
                                                        <td class="my-1 w-25"></td>
                                                        <td class="my-1 w-25"></td>
                                                        <td class="my-1 w-25"></td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Accordéon ingrédients -->
                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingThree">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseThree"
                                                    aria-expanded="true" aria-controls="collapseThree">
                                                {{ __("textes.formules_show_accordeon_ingredients") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree">
                                        <div class="accordion-body">


                                            <!-- Tableau contenant toutes les symptomes lié à ce syndrome -->
                                            <table class="table w-100">
                                                <tr class="border-bottom">
                                                    <td>
                                                        <strong>{{ __("textes.formules_show_accordeon_ingredients") }}</strong>
                                                    </td>
                                                    <td>
                                                        <strong>{{ __("textes.formules_show_accordeon_ingredients_ponderation") }}</strong>
                                                    </td>
                                                    <td>
                                                        <strong>{{ __("textes.formules_show_accordeon_ingredients_quantite") }}</strong>
                                                    </td>
                                                </tr>
                                            @if(!empty($ingredients))
                                                <!-- Pour chaque formule récupéré du tableau formules et contenu dans formule -->
                                                    @foreach($ingredients as $ingredient)
                                                        <tr class="table-line align-middle">
                                                            <!-- Pour chaque données récupéré de la formule et contenu dans donnees_formule -->
                                                            <td class="my-1 w-25">
                                                                <!-- Lien qui amène l'utilisateur vers la formule -->
                                                                <a href="{{ route('ingredientsAdmin.show', [ $ingredient->id]) }}"
                                                                   class="show-element-name"> {{ $ingredient->nom_chinois }} </a><br>
                                                                <a href="{{ route('ingredientsAdmin.show', [ $ingredient->id]) }}"
                                                                   class="show-element-secondary"> {{ $ingredient->nom }} </a><br>
                                                                <a href="{{ route('ingredientsAdmin.show', [ $ingredient->id]) }}"
                                                                   class="show-element-secondary fst-italic"> {{ $ingredient->nom_latin }} </a>
                                                            </td>
                                                            <!-- Affiche le score du syndrome -->
                                                            <td class="w-25"> {{ $ingredient->ponderation }} </td>
                                                            <td class="w-25"> {{ $ingredient->quantite }} mg</td>
                                                        </tr>
                                                    @endforeach

                                                @else
                                                <!-- si aucune formules n'as été trouvée -->
                                                    <tr class="table-line">
                                                        <td class="my-1 w-25">{{__("textes.ingredients_show_search_aucun")}}</td>
                                                        <td class="my-1 w-25"></td>
                                                        <td class="my-1 w-25"></td>
                                                        <td class="my-1 w-25"></td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    @endif
                </div>
        </div>
    </section>
@endsection
