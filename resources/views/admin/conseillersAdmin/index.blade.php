@extends('layouts.admin-template')
@section('title'){{__("textes.conseillersAdmin_page_nom")}}@endsection
@section('content')
    <!-- Titre de la page index -->
    <div class="index-name-div">
        <h1 class="index-name-text">{{__("textes.conseillersAdmin_index_titre")}}</h1>
    </div>

    <!-- Bouton +, dis " add " qui permet dajouter un praticiens en renvoyant l'utilisateur sur la page de creation-->
    <div>
        <a class="btn btn-add rounded-circle" href="{{ route('conseillersAdmin.create') }}" role="button"> <i
                class="fas fa-plus add-icon mt-3"> </i></a>
    </div>

    <div class="container">

        <!-- Créer un formulaire qui va permettre de chercher des praticiens dans la liste en appelant la fonction praticiens.search -->
        <form action="{{ route('conseillersAdmin.search') }}" class="d-flex mb-5" method="GET">

            <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui être transmis par la suite au code -->
            <input class="form-control me-2 list-search" type="text" name="search"
                   placeholder="{{__("textes.conseillersAdmin_index_search_placeholder")}}"
                   @if(request('search')) value="{{ request('search') }}" @endif aria-label="Search">
            <!-- Bouton affichant une icone et permettant de valider la recherche -->
            <button class="btn btn-small" type="submit"><i class="fas fa-search icon-size icon-white"></i></button>

            <!-- Si l'utilisateur a effectué une recherche -->
        @if(request('search'))
            <!-- Affiche une îcone qui ramène l'utilisateur vers la page contenant la liste de tous les praticiens -->
                <a href="{{ route('conseillersAdmin.search') }}">
                    <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                </a>
            @endif

        </form>

        @if(!empty($conseillers))

            <table class="w-100 table">
                <!-- Titre des catégories du tableau -->
                <thead class="w-100">
                <tr>
                    <th class="liste-titre">{{__("textes.conseillersAdmin_index_tableau_colone_une")}}</th>
                    <th class="liste-titre">{{__("textes.conseillersAdmin_index_tableau_colone_deux")}}</th>
                </tr>
                </thead>
                <!-- Pour chaque praticiens récupérés du tableau praticiens et contenu dans praticiens -->
                @foreach($conseillers as $conseiller)
                    <tbody>
                    <tr>
                        <!-- Affiche le code du praticiens -->
                        <th class="bord-color"><span class="liste-text"> {{ $conseiller->code}} </span></th>
                        <!-- On affiche le nom du praticiens qui si cliqué amène l'utilisateur vers ce praticiens -->
                        <td class="bord-color"><span class="liste-text"> <a
                                    href="{{ route("conseillersAdmin.show", [$conseiller->id]) }}"
                                    class="liste-text"> {{wordwrap($conseiller->nom,60)}} </a> </span></td>
                    </tr>
                    </tbody>
                @endforeach
            </table>

        @else
            <div>
                <!-- On affiche un message expliquant qu'aucun praticiens n'a été trouvé -->
                <em class="fs-5">{{__("textes.conseillersAdmin_index_aucun")}}</em>
            </div>
        @endif
    </div>

@endsection
