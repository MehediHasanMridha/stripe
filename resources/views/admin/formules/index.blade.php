@extends('layouts.admin-template')
@section('title'){{__("textes.formulesAdmin_page_nom")}}@endsection
@push('script')
    <script type="text/javascript" src="{{ URL::asset('js/tri.js') }}"></script>
@endpush
@section('content')

    <!-- Titre de la page index -->
    <div class="index-name-div">
        <h1 class="index-name-text">{{ __("textes.formulesAdmin_index_titre") }}</h1>
    </div>

    <!-- Bouton +, dis " add " qui permet dajouter un syndrome en renvoyant l'utilisateur sur la page create -->
    <div>
        <a class="btn btn-add rounded-circle" href="{{ route('formulesAdmin.create') }}" role="button"> <i
                class="fas fa-plus add-icon mt-3"> </i></a>
    </div>

    <div class="container">
        <!-- Créer un formulaire qui va permettre de chercher des syndromes dans la liste en appelant la foonction syndromes.search -->
        <form action="{{ route('formulesAdmin.search') }}" class="d-flex align-items-center mb-5" method="GET">

            <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui être transmis par la suite au code -->
            <select class="select2 form-control me-2 list-search" name="search" id="search" aria-label="Search">
                <option disabled selected value>{{__("textes.formules_liste_search_placeholder")}}</option>
            </select>
            <!-- Bouton affichant une icone et permettant de valider la recherche -->
            <button class="btn btn-small" type="submit"><i class="fas fa-search icon-size icon-white"></i></button>

            <!-- Si l'utilisateur a effectué une recherche -->
        @if(request('search'))
            <!-- Affiche une îcone qui ramène l'utilisateur vers la page contenant la liste de tous les syndromes -->
                <a href="{{ route('formulesAdmin.index') }}">
                    <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                </a>
            @endif

        </form>

        @if(!empty($formules))

            <table class="w-100 table">
                <!-- Titre des catégories du tableau -->
                <thead class="w-100">
                <tr>
                    <th class="liste-titre link-primary"
                        onclick="tri_tbody('trier',0,'int-reverse')">{{__("textes.formulesAdmin_index_tableau_colone_1")}}</th>
                    <th class="liste-titre link-primary"
                        onclick="tri_tbody('trier',1)">{{__("textes.formulesAdmin_index_tableau_colone_2")}}</th>
                    <th class="liste-titre link-primary"
                        onclick="tri_tbody('trier',2)">{{__("textes.formulesAdmin_index_tableau_colone_3")}}</th>
                </tr>
                </thead>
                <!-- Pour chaque syndromes récupéré du tableau syndromes et contenu dans syndrome -->
                <tbody id="trier">
                @foreach($formules as $formule)
                    <tr>
                        <!-- Affiche l'id du syndrome -->
                        <th class="bord-color"><span class="liste-text"> {{ $formule->id}} </span></th>
                        <!-- On affiche le nom de du syndrome qui si cliqué amène l'utilisateur vers ce syndrome -->
                        <td class="bord-color"><span class="liste-text"> <a
                                    href="{{ route("formulesAdmin.show", [$formule->id]) }}"
                                    class="liste-text"> {{wordwrap($formule->nom,60)}} </a> </span></td>
                        <td class="bord-color"><span class="liste-text"> <a
                                    href="{{ route("formulesAdmin.show", [$formule->id]) }}"
                                    class="liste-text"> {{wordwrap($formule->nom_chinois,60)}} </a> </span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        @else
            <div>
                <!-- On affiche un message expliquant qu'aucun syndrome n'a été trouvé -->
                <em class="fs-5">{{__("textes.formules_index_aucun")}}</em>
            </div>
        @endif
    </div>

    <!-- Ajout Select2 aux balises <select> -->
    <script>
        let donneesBrutes = {!! json_encode($formules) !!};

        let formules = [
            {"text": {!! json_encode(__("textes.formulesAdmin_index_tableau_colone_2")) !!}, "children": []},
            {"text": {!! json_encode(__("textes.formulesAdmin_index_tableau_colone_3")) !!}, "children": []}
        ];
        for (const formule in donneesBrutes) {
            formules[0].children.push({"text": donneesBrutes[formule].nom});
            formules[1].children.push({"text": donneesBrutes[formule].nom_chinois});
        }

        // Trie du tableau par ordre alphabétique
        formules[0].children.sort((a, b) => a.text.localeCompare(b.text));
        formules[1].children.sort((a, b) => a.text.localeCompare(b.text));

        // Paramétrage de Select2
        $(document).ready(function () {
            $('.select2').select2({
                theme: "bootstrap-5",
                selectOnClose: true,
                data: formules,
            });
        });
    </script>

@endsection
