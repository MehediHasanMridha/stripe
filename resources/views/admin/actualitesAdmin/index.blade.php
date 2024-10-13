@extends('layouts.admin-template')
@section('title'){{ __("textes.actualitesAdmin_page_nom") }}@endsection
@section('content')
    <!-- Titre de la page index -->
    <div class="index-name-div">
        <h1 class="index-name-text">{{ __("textes.actualitesAdmin_index_titre") }}</h1>
    </div>

    <!-- Bouton +, dis " add " qui permet d'ajouter un praticiens en renvoyant l'utilisateur sur la page create -->
    <div>
        <a class="btn btn-add rounded-circle" href="{{ route('actualitesAdmin.create') }}" role="button"> <i
                class="fas fa-plus add-icon mt-3"> </i></a>
    </div>

    <div class="container-fluid container-xl">

        <!-- Créer un formulaire qui va permettre de chercher des actualites dans la liste en appelant la fonction actualites.search -->
        <form action="{{ route('actualitesAdmin.search') }}" class="d-flex mb-5" method="GET">

            <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui va être transmis par la suite au code -->
            <input class="form-control me-2 list-search" type="text" name="search"
                   placeholder="{{ __("textes.actualitesAdmin_index_search_placeholder") }}"
                   @if(request('search')) value="{{ request('search') }}" @endif aria-label="Search">
            <!-- Bouton affichant une îcone et permettant de valider la recherche -->
            <button class="btn btn-small" type="submit"><i class="fas fa-search icon-size icon-white"></i></button>

            <!-- Si l'utilisateur a effectué une recherche -->
        @if(request('search'))
            <!-- Affiche une îcone qui ramène l'utilisateur vers la page contenant la liste de tous les praticiens -->
                <a href="{{ route('actualitesAdmin.index') }}">
                    <button type="button" class="btn-close ms-2 icon-size x-size" aria-label="Close"></button>
                </a>
            @endif

        </form>

        @if(!empty($actualites))

            <table class="w-100 table">
                <!-- Titre des catégories du tableau -->
                <thead class="w-100">
                <tr>
                    <th class="liste-titre">{{ __("textes.actualitesAdmin_index_tableau_colone_une") }}</th>
                    <th class="liste-titre">{{ __("textes.actualitesAdmin_index_tableau_colone_deux") }}</th>
                    <th class="liste-titre">{{ __("textes.actualitesAdmin_index_tableau_colone_trois") }}</th>
                    <th class="liste-titre">{{ __("textes.actualitesAdmin_index_tableau_colone_quatre") }}</th>
                </tr>
                </thead>
                <!-- Pour chaque actualités récupérés du tableau praticiens et contenu dans praticiens -->
                @foreach($actualites as $actualite)
                    <tbody>
                    <tr>
                        <!-- Affiche l'id de l'actualités -->
                        <th class="bord-color"><span class="liste-text"> {{ $actualite->id }} </span></th>
                        <!-- On affiche le titre de l'actualité qui si cliqué amène l'utilisateur vers cet article -->
                        <td class="bord-color"><span class="liste-text"> <a
                                    href="{{ route("actualitesAdmin.show", [$actualite->id]) }}"
                                    class="liste-text text-decoration-none"> {{ $actualite->titre }} </a> </span></td>
                        <td class="bord-color"><span class="liste-text">{{ $actualite->date }}</span></td>
                        <td class="bord-color">
                                    <span class="liste-text">
                                        @if(sizeof([$actualite->categories])>0)
                                            @foreach($actualite->categories as $key=>$categorie)
                                                {{ $categorie.(intval($key)===sizeof((array) $actualite->categories)?"":" | ")}}
                                            @endforeach
                                        @endif
                                    </span>
                        </td>
                    </tr>
                    </tbody>
                @endforeach
            </table>

        @else
            <div>
                <!-- On affiche un message expliquant qu'aucun praticiens n'a été trouvé -->
                <em class="fs-5">{{ __("textes.actualitesAdmin_index_aucun") }}</em>
            </div>
        @endif
    </div>

@endsection
