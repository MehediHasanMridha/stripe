@extends('layouts.admin-template')
@section('title'){{__("textes.symptomes_page_nom")}}@endsection
@push('script')
    <script type="text/javascript" src="{{ URL::asset('js/tri.js') }}"></script>
@endpush
@section('content')
    <section class="section">
        <div class="container">
            <!-- Si des données sur le symptome existe -->
            @if(!empty($symptome))
                <!-- Pour chaque données récupéré du symptome et contenu dans donnees_symptome -->
                <div class="mb-3 mt-4">
                    <!-- div qui permet d'afficher les éléments sur la même ligne mais espacés -->
                    <div class="d-flex align-items-center justify-content-between">
                        <div></div>
                        <!-- Affiche dans un titre de niveau 3 le nom générique du symptome et son id -->
                        <h3> {{ $symptome->id }} - {{ $symptome->traduction->text }}</h3>
                        <!-- Affiche une croix permettant de revenir a la page index -->
                        <a href="{{ route('symptomes.index') }}">
                            <button type="button" class="btn-close ms-2 show-margin-right x-size" aria-label="Close"></button>
                        </a>
                    </div>
                </div>

                <div class="container">
                        <div class="row">

                            <div class="d-flex align-items-center justify-content-center flex-wrap">

                                <a href="{{ route('symptomes.edit', [$symptome->id]) }}" class="btn btn-admin btn-color text-white fw-bold mx-2 mb-3">{{__("textes.symptomes_show_btn_edit")}}</a>
                                <a href="{{ route('symptomes.traduction', [$symptome->id]) }}" class="btn btn-admin btn-color text-white fw-bold mx-2 mb-3">{{__("textes.symptomes_show_btn_trad")}}</a>
                                <form action="{{ route('symptomes.destroy', [$symptome->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Êtes-vous sûr ?')" type="submit" class="btn btn-admin btn-color text-white fw-bold mx-2 mb-3">{{__("textes.symptomes_show_btn_supp")}}</button>
                                </form>

                            </div>

                            <div class="col my-4">

                                <!-- div contennant tous les accordéons -->
                                <div class="accordion w-100" id="accordionExample">

                                    <!-----------------------
                                    -  Accordéon Synonymes  -
                                    ------------------------>

                                    <div class="accordion-item">
                                        <!-- Partie supérieur -->
                                        <div class="accordion-header" id="headingTwo">
                                            <h2 class="mb-0">
                                                <button class="btn accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                    {{__("textes.symptomes_show_accordeon_synonymes")}}
                                                </button>
                                            </h2>
                                        </div>

                                        <!-- Partie déroulante  -->

                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" >
                                            <div class="accordion-body">
                                                <!-- Tableau contenant tous les synonymes -->
                                                <table class="w-100">
                                                    <tr class="border-bottom">
                                                        <td class="w-100"><strong>{{__("textes.symptomes_show_accordeon_synonymes_item")}}</strong></td>
                                                    </tr>
                                                    <!-- Pour chaque synonyme on affiche le nom -->
                                                    @foreach($synonymes as $synonyme)
                                                        <tr class="table-line">
                                                            <td class="my-1"> {{ $synonyme->traduction->text }} </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>

                                    </div>

                                    <!-----------------------
                                    -  Accordéon Syndromes  -
                                    ------------------------>

                                    <div class="accordion-item">
                                        <!-- Partie supérieur -->
                                        <div class="accordion-header" id="headingThree">
                                            <h2 class="mb-0">
                                                <button class="btn accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                    {{__("textes.symptomes_show_accordeon_syndromes")}}
                                                </button>
                                            </h2>
                                        </div>

                                        <!-- Partie déroulante  -->
                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" >
                                            <div class="accordion-body">
                                                <!-- Tableau contenant tous les syndromes et son score lié à cette formule -->
                                                <table class="w-100">
                                                    <tr class="border-bottom">
                                                        <td class="cursor-pointer" onclick="tri_tbody('trier',0,'int')"><strong>{{__("textes.symptomes_show_accordeon_syndromes_item_syndromes")}}</strong></td>
                                                        <td class="cursor-pointer" onclick="tri_tbody('trier',1)"><strong>{{__("textes.symptomes_show_accordeon_syndromes_item_nom")}}</strong></td>
                                                        <td class="cursor-pointer" onclick="tri_tbody('trier',2,'int')"><strong>{{__("textes.symptomes_show_accordeon_syndromes_item_score")}}</strong></td>
                                                    </tr>
                                                    @if(!empty($syndromes))
                                                        <!-- Pour chaque symptome récupéré du tableau symptomes et contenu dans symptome -->
                                                        <tbody id="trier">
                                                            @foreach($syndromes as $syndrome)
                                                                <tr class="my-1">
                                                                    <!-- Pour chaque données récupéré du symptome et contenu dans donnees_symptome -->
                                                                    <!-- Affiche l'id du syndromes -->
                                                                    <td class="w-25"> <a href="{{ route("syndromes.show", [$syndrome->id] )}}" class="text-decoration-none"> {{ $syndrome->id }} </a> </td>
                                                                    <!-- Affiche le nom du syndromes -->
                                                                    <td class="w-50"> <a href="{{ route("syndromes.show" ,[$syndrome->id]) }}" class="text-decoration-none"> {{ $syndrome->nom }} </a> </td>
                                                                    <!-- Affiche le score du syndrome -->
                                                                    <td class="w-25"> {{ $syndrome->score }} </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    @else
                                                    <!-- Affiche un message si aucun syndrome n'as été trouveé -->
                                                        <tr class="table-line">
                                                            <td class="my-1 w-25">{{__("textes.symptomes_show_accordeon_syndrome_search_aucun")}}</td>
                                                            <td class="my-1 w-50"></td>
                                                            <td class="my-1 w-25"></td>
                                                        </tr>
                                                    @endif
                                                </table>

                                            </div>
                                        </div>
                                    </div>

                                    <!-----------------------
                                    -  Accordéon Formules   -
                                    ------------------------>

                                    <div class="accordion-item">
                                        <!-- Partie supérieur -->
                                        <div class="accordion-header" id="headingFour">
                                            <h2 class="mb-0">
                                                <button class="btn accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                                    {{__("textes.symptomes_show_accordeon_formules")}}
                                                </button>
                                            </h2>
                                        </div>

                                        <!-- Partie déroulante  -->
                                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <!-- Tableau contenant toutes les formules lié à ce symptome -->
                                                <table class="w-100">
                                                    <tr class="border-bottom">
                                                        <td class="cursor-pointer" onclick="tri_tbody('trier2',0)"><strong>{{__("textes.symptomes_show_accordeon_formules_item_formules")}}</strong></td>
                                                        <td class="cursor-pointer" onclick="tri_tbody('trier2',1)"><strong>{{__("textes.symptomes_show_accordeon_formules_item_pinyin")}}</strong></td>
                                                        <td class="cursor-pointer" onclick="tri_tbody('trier2',2)"><strong>{{__("textes.symptomes_show_accordeon_formules_item_francais")}}</strong></td>
                                                        <td class="cursor-pointer" onclick="tri_tbody('trier2',3,'int')"><strong>{{__("textes.symptomes_show_accordeon_syndromes_item_score")}}</strong></td>
                                                    </tr>
                                                    @if(!empty($formules))
                                                        <!-- Pour chaque formule récupéré du tableau formules et contenu dans formule -->
                                                        <tbody id="trier2">
                                                            @foreach($formules as $formule)
                                                                <tr class="table-line">
                                                                    <!-- Pour chaque données récupéré de la formule et contenu dans donnees_formule -->
                                                                    <td class="my-1 w-25">
                                                                        <!-- Lien qui amène l'utilisateur vers la formule -->
                                                                        <a href="{{ route('formules.show', ["fr", $formule->id]) }}" class="text-decoration-none"> {{ $formule->nom }} </a>
                                                                    </td>
                                                                        <td  class="my-1 w-25">
                                                                        <a href="{{ route('formules.show', ["fr", $formule->id]) }}" class="text-decoration-none"> {{ $formule->nom_chinois }} </a>
                                                                    </td>
                                                                    <td  class="my-1 w-25">
                                                                        <a href="{{ route('formules.show', ["fr", $formule->id]) }}" class="text-decoration-none"> {{ $formule->nom_langue }} </a>
                                                                    </td>
                                                                    <!-- Affiche le score du syndrome -->
                                                                    <td class="w-25"> {{ $formule->score }} </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>

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
                            </div>
                        </div>
                    </div>

            <!-- Si des données sur l'ingrédient n'existe pas -->
            @else
                <div class="my-2 d-flex align-items-center">
                    <!-- Affiche une icone qui ramène l'utilisateur à la page contenant la liste de toutes les formules -->
                    <a href="{{ route('symptomes.index')}}" class="mx-3">
                        <i class="fas fa-long-arrow-alt-left fs-2" style="color: black;"></i>
                    </a>
                    <!-- Texte annonçant qu'aucune donnée sur cette ingrédient trouvé n'a été trouvé -->
                    <em class="fs-5 ms-3">{{__("textes.symptomes_show_aucun")}}</em>
                </div>
            @endif
            </div>
        </div>
    </section>
    <style>
        .cursor-pointer{
            cursor:pointer;
            color:dodgerblue;
        }
    </style>
@endsection

