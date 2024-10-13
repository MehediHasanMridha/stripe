@extends('layouts.admin-template')
@section('title'){{__("textes.syndromes_page_nom")}}@endsection
@push('script')
    <script type="text/javascript" src="{{ URL::asset('js/tri.js') }}"></script>
@endpush

@section('content')
    <section>
        <div class="container-fluid container-xl">
            <!-- Si des données sur le syndrome existe -->
        @if(!empty($syndrome))
            <!-- Pour chaque données récupéré du syndrome et contenu dans donnees_syndrome -->
                <div class="mb-3 mt-4">
                    <!-- div qui permet d'afficher les éléments sur la même ligne mais espacés -->
                    <div class="d-flex align-items-center justify-content-between">
                        <div></div>
                        <!-- Affiche dans un titre de niveau 3 le nom générique du syndrome et son id -->
                        <h3> {{ $syndrome->id }} - {{ $syndrome->nom }}</h3>
                        <!-- Affiche une croix permettant de revenir a la page index -->
                        <a href="{{ route('syndromesAdmin.index') }}">
                            <button type="button" class="btn-close ms-2 show-margin-right x-size"
                                    aria-label="Close"></button>
                        </a>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">

                        <div class="d-flex align-items-center">

                            <a href="{{ route('syndromesAdmin.edit', [ $syndrome->id]) }}"
                               class="btn btn-admin btn-color text-white fw-bold me-4">{{__("textes.syndromes_show_btn_edit")}}</a>
                        <!--<a href="{{ route('syndromes.traduction', [$syndrome->id]) }}" class="btn btn-admin btn-color text-white fw-bold me-4">{{__("textes.syndromes_show_btn_trad")}}</a>-->
                            <form action="{{ route('syndromesAdmin.destroy', [$syndrome->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Êtes-vous sûr ?')"
                                        class="btn btn-admin btn-color text-white me-4 fw-bold">{{__("textes.syndromes_show_btn_supp")}}</button>
                            </form>

                        </div>

                        <div class="col my-4">

                            <!-- div contennant tous les accordéons -->
                            <div class="accordion w-100" id="accordionExample">

                                <!-- Affichage de l'image -->
                                <div class="mb-3">
                                    <h4>{{__("textes.syndromes_show_image")}}</h4>
                                    @if (!empty($syndrome->image))
                                        <img src="{{asset($syndrome->image)}}" class="img-thumbnail thumbnail-width"
                                             alt="{{__("textes.syndromes_show_image_alt")}}">
                                    @else
                                        <em class="fs-5 text-muted">{{__("textes.syndromes_show_image_none")}}</em>
                                    @endif
                                </div>

                                <!-- Accordéon symptomes -->
                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingFour">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed" type="button"
                                                    data-toggle="collapse" data-target="#collapseFour"
                                                    aria-expanded="true" aria-controls="collapseFour">
                                                {{__("textes.syndromes_show_accordeon_symptomes")}}
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
                                                        <strong>{{__("textes.syndromes_show_accordeon_synonymes_item_symptomes")}}</strong>
                                                    </td>
                                                    <td>
                                                        <strong>{{__("textes.syndromes_show_accordeon_synonymes_item_score")}}</strong>
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


                            </div>
                        </div>
                    </div>


                    @endif
                </div>
        </div>
    </section>
@endsection
