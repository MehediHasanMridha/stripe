@extends('layouts.admin-template')
@section('title'){{ __('textes.rechercheAdmin_page_nom') }}@endsection
@section('content')
    <section>
        <div class="container">
            <div>
                <h1 class="index-name-text">{{ __("textes.headerAdmin_link_rechercher") }}</h1>
            </div>

            <form action="{{ route('recherche.search') }}" method="GET" class="phone-margin mb-5 d-flex">
                <input type="text" class="form-control me-2 form-search" name="search"
                       placeholder="{{__('textes.rechercheAdmin_chercher')}}"
                       @if(request('search')) value="{{ request('search') }}" @endif autocomplete="off"
                       aria-label="Search">
                <button class="btn btn-form-color text-white" type="submit" onclick="showLoading()"><i
                        class="fas fa-search icon-size"></i></button>

                <!-- Si l'utilisateur a effectué une recherche -->
            @if(request('search'))
                <!-- Affiche une îcone qui ramène l'utilisateur vers la page contenant la liste de tous les ingrédients -->
                    <a href="{{ route('recherche.index') }}">
                        <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                    </a>
                @endif
            </form>

            <div id="loading">
                <div id="loading-content"></div>
            </div>

            <!-- Si l'utilisateur a effectué une recherche -->
            @if(request()->input('search'))
                <script type="text/javascript">hideLoading()</script>
                <!-- Si au moins un élément a été trouvé dans la base de données -->
                @if (!empty($liste_symptomes))
                    @php($idx = 1)
                    <table class="table table-space table-borderless">
                        <tr>
                            <th class="table1-id text-size">{{ __('textes.rechercheAdmin_search_th_symptome') }}</th>
                            <th class="table1-nom text-size">{{ __('textes.rechercheAdmin_search_th_nom') }}</th>
                            <th class="table1-nb text-size">{{ __('textes.rechercheAdmin_search_th_syndrome') }}</th>
                            <th class="table1-nb text-size">{{ __('textes.rechercheAdmin_search_th_formule') }}</th>
                            <th class="table1-nb text-size">{{ __('textes.rechercheAdmin_search_th_ingredient') }}</th>
                        </tr>
                    </table>


                    <div class="accordion" id="accordionNiveau1">

                        <!-----------------------
                        - Accordéon Niveau 1
                        ------------------------>

                        @foreach($liste_symptomes as $id_symptome => $symptome)
                            <div class="accordion-item">
                                <!-- Partie supérieur -->
                                <div class="accordion-header" id="heading{{$id_symptome}}">
                                    <h2 class="mb-0">
                                        <button class="btn accordion-button collapsed text-show-size" type="button"
                                                data-toggle="collapse" data-target="#collapse{{$id_symptome}}"
                                                aria-expanded="true" aria-controls="collapse{{$id_symptome}}">
                                            <table class="table table-borderless w-100">
                                                <tr>
                                                    <th class="table1-id text-size">{{ $id_symptome }}</th>
                                                    <th class="table1-nom text-size"><span
                                                            onclick="window.location.href = '{{ route('symptomes.show', [$id_symptome]) }}'"
                                                            class="text-style">{{ $symptome['nom'] }}</span></th>
                                                    <th class="table1-nb text-size">{{ $symptome['nb_syndromes'] }}</th>
                                                    <th class="table1-nb text-size">{{ $symptome['nb_formules'] }}</th>
                                                    <th class="table1-nb text-size">{{ $symptome['nb_ingredients'] }}</th>
                                                </tr>
                                            </table>
                                        </button>
                                    </h2>
                                </div>

                                <!-- Partie déroulante  -->
                                <div id="collapse{{$id_symptome}}" class="collapse"
                                     aria-labelledby="heading{{$id_symptome}}" data-bs-parent="#accordionNiveau1">
                                    <div class="accordion-body">

                                        <!-----------------------
                                        - Accordéon Niveau 2
                                        ------------------------>

                                        <div class="accordion" id="accordionNiveau2-{{$id_symptome}}">

                                            <!-----------------------
                                            - Accordéon Niveau 2
                                            - Syndromes
                                            ------------------------>

                                            <div class="accordion-item">
                                                <!-- Partie supérieur -->
                                                <div class="accordion-header" id="heading{{$id_symptome}}Syndrome">
                                                    <h2 class="mb-0">
                                                        <button class="btn accordion-button collapsed text-show-size"
                                                                type="button" data-toggle="collapse"
                                                                data-target="#collapse{{$id_symptome}}Syndrome"
                                                                aria-expanded="true"
                                                                aria-controls="collapse{{$id_symptome}}Syndrome">
                                                            <span
                                                                class="text-size">{{ __('textes.rechercheAdmin_search_accordeon_syndromes') }}</span>
                                                        </button>
                                                    </h2>
                                                </div>

                                                <!-- Partie déroulante  -->
                                                <div id="collapse{{$id_symptome}}Syndrome" class="collapse"
                                                     aria-labelledby="heading{{$id_symptome}}Syndrome">
                                                    <div class="accordion-body">
                                                        <table class="table w-100 table-striped table-hover">
                                                            @foreach($symptome['syndromes'] as $id_syndrome => $syndrome)
                                                                <tr>
                                                                    <td class="text-size"><a
                                                                            class="text-decoration-none text-style">{{ $syndrome }}</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-----------------------
                                            - Accordéon Niveau 2
                                            - Formules
                                            ------------------------>

                                            <div class="accordion-item">
                                                <!-- Partie supérieur -->
                                                <div class="accordion-header" id="heading{{$id_symptome}}Formule">
                                                    <h2 class="mb-0">
                                                        <button class="btn accordion-button collapsed text-show-size"
                                                                type="button" data-toggle="collapse"
                                                                data-target="#collapse{{$id_symptome}}Formule"
                                                                aria-expanded="true"
                                                                aria-controls="collapse{{$id_symptome}}Formule">
                                                            <span
                                                                class="text-size">{{ __('textes.rechercheAdmin_search_accordeon_formules') }}</span>
                                                        </button>
                                                    </h2>
                                                </div>

                                                <!-- Partie déroulante  -->
                                                <div id="collapse{{$id_symptome}}Formule" class="collapse"
                                                     aria-labelledby="heading{{$id_symptome}}Formule">
                                                    <div class="accordion-body">
                                                        <table class="table table-borderless w-100">
                                                            @foreach($symptome['formules'] as $id_formule => $formule)
                                                                @php($idx++)
                                                                <tr>
                                                                    <th>

                                                                        <!-----------------------
                                                                        - Accordéon Niveau 3
                                                                        ------------------------>

                                                                        <div class="accordion"
                                                                             id="accordionNiveau3-{{$id_symptome}}{{$idx}}">
                                                                            <div class="accordion-item">
                                                                                <!-- Partie supérieur -->
                                                                                <div class="accordion-header"
                                                                                     id="heading{{$id_symptome}}Ingredient{{$idx}}">
                                                                                    <h2 class="mb-0">
                                                                                        <button
                                                                                            class="btn accordion-button collapsed text-show-size"
                                                                                            type="button"
                                                                                            data-toggle="collapse"
                                                                                            data-target="#collapse{{$id_symptome}}Ingredient{{$idx}}"
                                                                                            aria-expanded="true"
                                                                                            aria-controls="collapse{{$id_symptome}}Ingredient{{$idx}}">
                                                                                            <table
                                                                                                class="table table-borderless w-100">
                                                                                                <tr>
                                                                                                    <th class="table2-nom text-size">
                                                                                                        <span
                                                                                                            class="text-style">{{ $formule['nom'] }}</span>
                                                                                                    </th>
                                                                                                    <th class="table2-zh text-size">{{ $formule['nom_chinois'] }}</th>
                                                                                                    <th class="table2-fr text-size">{{ $formule['nom_langue'] }}</th>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </button>
                                                                                    </h2>
                                                                                </div>

                                                                                <!-- Partie déroulante  -->
                                                                                <div
                                                                                    id="collapse{{$id_symptome}}Ingredient{{$idx}}"
                                                                                    class="collapse"
                                                                                    aria-labelledby="heading{{$id_symptome}}Ingredient{{$idx}}">
                                                                                    <div class="accordion-body">
                                                                                        <table
                                                                                            class="table w-100 table-striped table-hover">
                                                                                            @foreach($formule["ingredients"] as $id_ingredient => $ingredient)
                                                                                                <tr>
                                                                                                    <td class="text-size">
                                                                                                        <a href="{{ route('ingredientsAdmin.show', [$id_ingredient]) }}"
                                                                                                           class="text-decoration-none text-style">{{ $ingredient }}</a>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </th>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Si aucun élément n'a été trouvé dans la base de données -->
                @else
                    <em class="text-muted fs-5">{{ __('textes.rechercheAdmin_search_aucun') }}</em>
            @endif
        @endif
        </div>

        <script type="text/javascript">
            function showLoading() {
                document.querySelector('#loading').classList.add('loading');
                document.querySelector('#loading-content').classList.add('loading-content');
            }

            function hideLoading() {
                document.querySelector('#loading').classList.remove('loading');
                document.querySelector('#loading-content').classList.remove('loading-content');
            }
        </script>
    </section>
@endsection
