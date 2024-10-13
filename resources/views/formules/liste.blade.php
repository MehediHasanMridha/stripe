@extends('layouts.template')
@section('title'){{__("textes.formules_page_nom")}}@endsection
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
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_liste") }}</li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <div class="d-flex align-items-center justify-content-center">
                <!-- Affiche le titre de la page -->
                <h2 class="text-center my-3">{{ __("textes.formules_index_titre") }}</h2>
            </div>

            <!-- Créer un formulaire qui va permettre de chercher des formules dans la liste en appelant la page formules.search -->
            @if($selectedLang == 'fr')
                <?php
                $formules = collect($formules)->sortBy('nom_langue')->toArray();
                ?>
            @elseif($selectedLang == 'cn')
                <?php
                $formules = collect($formules)->sortBy('nom_chinois')->toArray();
                ?>
            @else
                <?php
                $formules = collect($formules)->sortBy('nom')->toArray();
                ?>
            @endif

            <form action="{{ route('formules.search', [$selectedLang]) }}"
                  class="phone-margin d-flex align-items-center mb-5" method="GET">

                <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui être transmis par la suite au code -->
                <select class="select2 form-control me-2 list-search" name="search" id="search" aria-label="Search">
                    <option disabled selected value>{{__("textes.formules_liste_search_placeholder")}}</option>
                </select>
                <!-- Bouton affichant une icone et permettant de valider la recherche -->
                <button class="btn btn-small" type="submit"><i class="fas fa-search icon-size icon-white"></i></button>

                <!-- Si l'utilisateur a effectué une recherche -->
            @if(request('search'))
                <!-- Affiche une icone qui ramène l'utilisateur vers la page contenant la liste de tous les ingrédients -->
                    <a href="{{ route('formules.liste', [$selectedLang]) }}">
                        <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                    </a>
                @endif

            </form>

            <!-- Si la liste des formules n'est pas vide -->
            @if(!empty($formules))

            <!-- Pour chaque formule récupéré du tableau formules et contenu dans formule -->
                @foreach($formules as $formule)
                    <div class="phone-margin d-flex align-items-center">
                        <!-- On affiche une image -->
                        @if (!empty($formule->image))
                            <img src="{{asset($formule->image)}}" class="img-thumbnail circle me-5 image-list-width"
                                 alt="{{ __("textes.ingredientsAdmin_show_image_alt") }}">
                        @else
                            <div class="circle me-5">
                                @if ($selectedLang == 'fr')
                                    <strong>{{ mb_substr($formule->nom_langue, 0, 2, 'UTF-8') }}</strong>
                                @else
                                    <strong>{{ mb_substr($formule->nom_chinois, 0, 2, 'UTF-8') }}</strong>
                                @endif
                            </div>
                        @endif

                        <div class="d-flex flex-column">
                            <!-- On affiche le nom de la formule qui amène l'utilisateur vers la page de la formule -->
                            <a href="{{ route('formules.show', [$selectedLang, $formule->id]) }}"
                               class="fs-3 text-decoration-none">
                                <p class="list-element-name">
                                    <!-- Si la langue sélectionnée est fr, on affiche le nom français de la formule -->
                                @if($selectedLang == 'fr') {{ $formule->nom_langue }}
                                <!-- Si la langue sélectionnée est fr, on affiche le nom chinois de la formule -->
                                @elseif($selectedLang == 'cn') {{ $formule->nom_chinois }}
                                <!-- Sinon, on affiche le nom générique de la formule -->
                                    @else {{ $formule->nom }}

                                    @endif
                                </p>
                            </a>

                            <!-- On affiche la future description de l'ingrédient -->
                            <!--<p class="list-element-desc">Lorem ipsum dolor sit amet, consectetur?</p>-->
                        </div>

                    </div>
                    <!-- Ligne de séparation entre chaque ingrédient -->
                    <hr class="list-element-separation">
                @endforeach

            <!-- Si la liste des ingrédients est vide -->
            @else
                <div>
                    <!-- On affiche un message expliquant qu'aucune formule n'a été trouvé -->
                    <em class="fs-5">{{ __("textes.formules_liste_search_aucun") }}</em>
                </div>
        @endif
        </div>

        <!-- Ajout Select2 aux balises <select> -->
        <script>
            // Récupération des formules
            let donneesBrutes = {!! json_encode($all_formules) !!};
            let langueSelectionne = {!! json_encode($selectedLang) !!};

            let formules = [];
            for (const formule in donneesBrutes) {
                if (langueSelectionne === 'fr') {
                    formules.push(donneesBrutes[formule].nom_langue);
                } else if (langueSelectionne === 'cn') {
                    formules.push(donneesBrutes[formule].nom_chinois);
                } else {
                    formules.push(donneesBrutes[formule].nom);
                }
            }

            // Trie du tableau par ordre alphabétique
            formules.sort((a, b) => a.localeCompare(b));

            // Paramétrage de Select2
            $(document).ready(function () {
                $('.select2').select2({
                    theme: "bootstrap-5",
                    selectOnClose: true,
                    data: formules,
                });
            });
        </script>
    </section>
@endsection
