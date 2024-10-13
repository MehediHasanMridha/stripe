@extends('layouts.template')
@section('title'){{__("textes.ingredients_page_nom")}}@endsection
@section('content')
    <section>
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a class="arianne-link"
                                                                              href="{{ route('ingredients.index') }}">{{ __("textes.arianne_ingredients") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_liste") }}</li>
                </ol>
            </nav>
        </div>
        <div class="container">

            <div class="d-flex align-items-center justify-content-center">
                <!-- Affiche le titre de la page -->
                <h2 class="text-center my-3">{{ __("textes.ingredients_index_titre") }}</h2>
            </div>

            <!-- Créer un formulaire qui va permettre de chercher des ingrédients dans la liste en appelant la page ingredients.search -->
            @if($selectedLang == 'fr')
                <?php
                $ingredients = collect($ingredients)->sortBy('nom_langue')->toArray();
                ?>
            @elseif($selectedLang == 'cn')
                <?php
                $ingredients = collect($ingredients)->sortBy('nom_chinois')->toArray();
                ?>
            @else
                <?php
                $ingredients = collect($ingredients)->sortBy('nom_latin')->toArray();
                ?>
            @endif
            <form action="{{ route('ingredients.search', [$selectedLang]) }}"
                  class="phone-margin d-flex align-items-center mb-5" method="GET">

                <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui être transmis par la suite au code -->
                <select class="select2 form-control me-2 list-search" name="search" id="search" aria-label="Search">
                    <option disabled selected value>{{__("textes.ingredients_liste_search_placeholder")}}</option>
                </select>
                <!-- Bouton affichant une îcone et permettant de valider la recherche -->
                <button class="btn btn-small" type="submit"><i class="fas fa-search icon-size icon-white"></i></button>

                <!-- Si l'utilisateur a effectué une recherche -->
            @if(request('search'))
                <!-- Affiche une îcone qui ramène l'utilisateur vers la page contenant la liste de tous les ingrédients -->
                    <a href="{{ route('ingredients.liste', [$selectedLang]) }}">
                        <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                    </a>
                @endif

            </form>

            <!-- Si la liste des ingrédients n'est pas vide -->
            @if(!empty($ingredients))

            <!-- Pour chaque ingredient récupéré du tableau ingredients et contenu dans ingredient -->
                @foreach($ingredients as $ingredient)
                    <div class="phone-margin d-flex align-items-center">
                        <!-- On affiche une image -->
                        @if (!empty($ingredient->image))
                            <img src="{{asset($ingredient->image)}}" class="img-thumbnail circle me-5 image-list-width"
                                 alt="{{__("textes.ingredientsAdmin_show_image_alt")}}">
                        @else
                            <div class="circle me-5">
                                @if ($selectedLang == 'fr')
                                    <strong>{{ mb_substr($ingredient->nom_langue, 0, 2, 'UTF-8') }}</strong>
                                @elseif($selectedLang == 'cn')
                                    <strong>{{ mb_substr($ingredient->nom_chinois, 0, 2, 'UTF-8') }}</strong>
                                @else
                                    <strong>{{ mb_substr($ingredient->nom_latin, 0, 2, 'UTF-8') }}</strong>
                                @endif
                            </div>
                        @endif

                        <div class="d-flex flex-column">
                            <!-- On affiche le nom de l'ingrédient qui amène l'utilisateur vers la page de l'ingrédient -->
                            <a href="{{ route('ingredients.show', [$selectedLang, $ingredient->id]) }}"
                               class="fs-3 text-decoration-none">
                                @if ($selectedLang == 'fr')
                                    <p class="list-element-name">{{ $ingredient->nom_langue }}</p>
                                @elseif($selectedLang == 'cn')
                                    <p class="list-element-name">{{ $ingredient->nom_chinois }}</p>
                                @else
                                    <p class="list-element-name">{{ $ingredient->nom_latin }}</p>
                                @endif
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
                    <!-- On affiche un message expliquant qu'aucun ingrédient n'a été trouvé -->
                    <em class="fs-5">{{__("textes.ingredients_liste_search_aucun")}}</em>
                </div>
        @endif
        </div>

        <!-- Ajout Select2 aux balises <select> -->
        <script>
            // Récupération des ingredients
            let donneesBrutes = {!! json_encode($ingredients) !!};
            let langueSelectionne = {!! json_encode($selectedLang) !!};

            let ingredients = [];
            for (const ingredient in donneesBrutes) {
                if (langueSelectionne === 'fr') {
                    ingredients.push(donneesBrutes[ingredient].nom_langue);
                } else if (langueSelectionne === 'cn') {
                    ingredients.push(donneesBrutes[ingredient].nom_chinois);
                } else {
                    ingredients.push(donneesBrutes[ingredient].nom_latin);
                }
            }

            // Trie du tableau par ordre alphabétique
            ingredients.sort((a, b) => a.localeCompare(b));

            // Paramétrage de Select2
            $(document).ready(function () {
                $('.select2').select2({
                    theme: "bootstrap-5",
                    selectOnClose: true,
                    data: ingredients,
                });
            });
        </script>
    </section>
@endsection
