@extends('layouts.admin-template')
@section('title'){{__("textes.ingredientsAdmin_page_nom")}}@endsection
@push('script')
    <script type="text/javascript" src="{{ URL::asset('js/tri.js') }}"></script>
@endpush
@section('content')
    <!-- Titre de la page index -->
    <div class="index-name-div">
        <h1 class="index-name-text">{{__("textes.ingredientsAdmin_index_titre")}}</h1>
    </div>

    <!-- Bouton +, dis " add " qui permet dajouter un ingrédient en renvoyant l'utilisateur sur la page create -->
    <div>
        <a class="btn btn-add rounded-circle" href="{{ route('ingredientsAdmin.create') }}" role="button"> <i
                class="fas fa-plus add-icon mt-3"> </i></a>
    </div>

    <div class="container">

        <!-- Créer un formulaire qui va permettre de chercher des ingrédient dans la liste en appelant la fonction ingredients.search -->
        <form action="{{ route('ingredientsAdmin.search') }}" class="d-flex align-items-center mb-5" method="GET">

            <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui être transmis par la suite au code -->
            <select class="select2 form-control me-2 list-search" name="search" id="search" aria-label="Search">
                <option disabled selected value>{{__("textes.ingredientsAdmin_index_search_placeholder")}}</option>
            </select>
            <!-- Bouton affichant une icone et permettant de valider la recherche -->
            <button class="btn btn-small" type="submit"><i class="fas fa-search icon-size icon-white"></i></button>

            <!-- Si l'utilisateur a effectué une recherche -->
        @if(request('search'))
            <!-- Affiche une îcone qui ramène l'utilisateur vers la page contenant la liste de tous les ingrédients -->
            <a href="{{ route('ingredientsAdmin.index') }}">
                <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
            </a>
        @endif

        </form>

        @if(!empty($ingredients))

            <table class="w-100 table">
                <!-- Titre des catégories du tableau -->
                <thead class="w-100">
                <tr>
                    <th class="liste-titre link-primary"
                        onclick="tri_tbody('trier',0,'int-reverse')">{{__("textes.ingredientsAdmin_index_tableau_colone_une")}}</th>
                    <th class="liste-titre link-primary"
                        onclick="tri_tbody('trier',1)">{{__("textes.ingredientsAdmin_index_tableau_colone_deux")}}</th>
                </tr>
                </thead>
                <!-- Pour chaque ingrédients récupérés du tableau ingrédients et contenu dans ingrédients -->
                <tbody id="trier">
                @foreach($ingredients as $ingredient)
                    <tr>
                        <!-- Affiche l'id de l'ingrédients -->
                        <th class="bord-color"><span class="liste-text"> {{ $ingredient->id}} </span></th>
                        <!-- On affiche le nom de de l'ingrédient qui si cliqué amène l'utilisateur vers cet ingrédient -->
                        <td class="bord-color"><span class="liste-text"> <a
                                    href="{{ route("ingredientsAdmin.show", [$ingredient->id]) }}"
                                    class="liste-text"> {{ wordwrap($ingredient->nom_langue, 60) }} </a> </span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        @else
            <div>
                <!-- On affiche un message expliquant qu'aucun ingrédients n'a été trouvé -->
                <em class="fs-5">{{__("textes.ingredientsAdmin_index_aucun")}}</em>
            </div>
        @endif
    </div>

    <!-- Ajout Select2 aux balises <select> -->
    <script>
        // Récupération des ingredients
        let donneesBrutes = {!! json_encode($ingredients) !!};

        let ingredients = [];
        for (const ingredient in donneesBrutes) {
            ingredients.push(donneesBrutes[ingredient].nom_langue);
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
@endsection
