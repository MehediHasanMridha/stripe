@extends('layouts.template')
@section('title'){{ __("textes.syndromes_titre") }}@endsection
@section('content')
    <section>
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
                                                                              href="{{ route('syndromes.liste') }}">{{__("textes.arianne_syndromes") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $syndrome->nom  }}</li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <!-- Si des données sur ce syndrome existe -->
        @if(!empty($syndrome))
            <!-- Pour chaque données récupérés du syndrome et contenu dans donnees_syndrome -->
                <div class="d-flex align-items-center justify-content-between">
                    <!-- élément permettant de centrer le texte et de placer le bouton à droite -->
                    <div class="show-margin-left"></div>

                    <!-- Affiche dans un titre de niveau 2 le nom du syndrome -->
                    <h2 class="text-center my-2">{{ $syndrome->nom }}</h2>

                    <!-- Affiche une îcone qui ramène l'utilisateur en arrière -->
                    <button type="button" onclick="window.history.go(-1)"
                            class="btn-close ms-2 show-margin-right icon-size" aria-label="Close"></button>

                </div>

                <div class="row row-show">
                    <div class="col col-left my-4">
                        <div>
                            @if (!empty($syndrome->image))
                                <img src="{{ asset($syndrome->image) }}" class="img-thumbnail image-show-size"
                                     alt="{{ __("textes.ingredientsAdmin_show_image_alt") }}">
                            @else
                                <img src="{{ asset('img/empty.png') }}" class="img-thumbnail image-show-size"
                                     alt="{{ __("textes.ingredientsAdmin_show_image_alt") }}">
                            @endif
                        </div>
                    </div>
                    <div class="col my-4">
                        <!-- div contennant tous les accordéons -->
                        <div class="accordion w-lg-75 w-100" id="accordionExample">


                            <!-----------------------
                            - Accordéon Symptomes
                            ------------------------>


                            <div class="accordion-item">
                                <!-- Partie supérieur -->
                                <div class="accordion-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button
                                            class="btn accordion-button @if(!request('Symptomesearch')) collapsed @endif"
                                            type="button" data-toggle="collapse" data-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                            <i class="fas fa-stethoscope me-2"></i> {{ __("textes.syndromes_show_symptomes") }}
                                        </button>
                                    </h2>
                                </div>

                                <!-- Partie déroulante  -->
                                <div id="collapseOne" class="collapse @if(request('Symptomesearch')) show @endif"
                                     aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <!-- Tableau contenant tous les symptômes lié à ce syndrome -->
                                        <table class="w-100 ">
                                            <tr class="border-bottom">
                                                <td><strong>{{ __("textes.syndromes_show_symptomes") }}</strong></td>
                                            </tr>
                                            <!-- Pour chaque symptôme récupéré du tableau symptomes et contenu dans symptome -->
                                            @foreach($symptomes as $symptome)
                                                <tr>
                                                    <td class="my-1">{{ $symptome->traduction->text }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>


                                <!-----------------------
                                - Accordéon Formules
                                ------------------------>

                                <div class="accordion-item">
                                    <!-- Partie supérieur -->
                                    <div class="accordion-header" id="headingTwo">
                                        <h2 class="mb-0">
                                            <button class="btn accordion-button collapsed " type="button"
                                                    data-toggle="collapse" data-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                <i class="fas fa-capsules me-2"></i>{{ __("textes.syndromes_show_formules") }}
                                            </button>
                                        </h2>
                                    </div>

                                    <!-- Partie déroulante  -->
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                         data-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <!-- Tableau contenant les 3 formules les plus probables lié au 5 meilleurs symptomes lié à ce syndrome -->
                                            <table class="w-100 ">
                                                <tr class="border-bottom">
                                                    <td colspan="3">
                                                        <strong>{{ __("textes.syndromes_show_formules") }}</strong></td>
                                                </tr>
                                                <!-- Pour chaque formule récupéré du tableau formules et contenu dans formule -->
                                                @foreach($formules as $formule)
                                                    <tr class="my-1">
                                                        <td>
                                                            <a href="{{ route('formules.show', [ 'fr', $formule->id]) }}"
                                                               class="show-element-name">{{ $formule->nom_chinois }}</a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('formules.show', [ 'fr', $formule->id]) }}"
                                                               class="show-element-name">{{ $formule->nom_langue }}</a>
                                                        </td>
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

        </div>

        <!-- Si des données sur le syndrome n'existe pas -->
        @else
            <div class="my-2 d-flex align-items-center">
                <!-- Affiche une îcone qui ramène l'utilisateur à la page contenant la liste de tous les syndromes -->
                <a href="{{ route('ingredients.liste', [ $SelectedLang]) }}" class="mx-3">
                    <i class="fas fa-long-arrow-alt-left fs-2" style="color: black;"></i>
                </a>
                <!-- Texte annonçant qu'aucune donnée sur ce syndrome n'a été trouvé -->
                <em class="fs-5 ms-3">{{ __("textes.syndromes_show_aucune") }}</em>
            </div>
        @endif
    </section>
@endsection
