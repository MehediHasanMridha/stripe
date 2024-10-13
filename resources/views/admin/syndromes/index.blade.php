@extends('layouts.admin-template')
@section('title'){{__('textes.syndromes_syndromes')}}@endsection
@push('script')
    <script type="text/javascript" src="{{ URL::asset('js/tri.js') }}"></script>
@endpush
@section('content')
<section class="section">
    <div class="container">
        <!-- Titre de la page index -->
        <div class="index-name-div">
            <h1 class="index-name-text">{{__('textes.syndromes_syndromes')}}</h1>
        </div>

        <!-- Bouton +, dis " add " qui permet dajouter un syndrome en renvoyant l'utilisateur sur la page create -->
        <div>
            <a class="btn btn-add rounded-circle" href="{{ route('syndromesAdmin.create') }}" role="button"> <i class="fas fa-plus add-icon mt-3"> </i></a>
        </div>

        <div class="container">

            <!-- Créer un formulaire qui va permettre de chercher des syndromes dans la liste en appelant la foonction syndromes.search -->
            <form action="{{ route('syndromesAdmin.search') }}" class="d-flex align-items-center mb-5" method="GET">

                <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui être transmis par la suite au code -->
                <select class="select2 form-control me-2 list-search" name="search" id="search" aria-label="Search">
                    <option disabled selected value>{{__("textes.syndromes_index_search_placeholder")}}</option>
                </select>
                <!-- Bouton affichant une icone et permettant de valider la recherche -->
                <button class="btn btn-small" type="submit"><i class="fas fa-search icon-size icon-white"></i></button>

                <!-- Si l'utilisateur a effectué une recherche -->
                @if(request('search'))
                    <!-- Affiche une îcone qui ramène l'utilisateur vers la page contenant la liste de tous les syndromes -->
                    <a href="{{ route('syndromesAdmin.index') }}">
                        <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                    </a>
                @endif

            </form>

            @if(!empty($syndromes))

                <table class="w-100 table">
                    <!-- Titre des catégories du tableau -->
                    <thead class="w-100">
                        <tr>
                            <th class="liste-titre link-primary" onclick="tri_tbody('trier',0,'int-reverse')">{{__('textes.syndromes_id')}}</th>
                            <th class="liste-titre link-primary" onclick="tri_tbody('trier',1)">{{__('textes.syndromes_nom')}}</th>
                        </tr>
                    </thead>
                    <!-- Pour chaque syndromes récupéré du tableau syndromes et contenu dans syndrome -->
                    <tbody id="trier">
                    @foreach($syndromes as $syndrome)
                        <tr>
                            <!-- Affiche l'id du syndrome -->
                            <th class="bord-color"> <span class="liste-text"> {{ $syndrome->id}} </span> </th>
                            <!-- On affiche le nom de du syndrome qui si cliqué amène l'utilisateur vers ce syndrome -->
                            <td class="bord-color"> <span class="liste-text"> <a href="{{ route("syndromesAdmin.show", [$syndrome->id]) }}" class="liste-text"> {{wordwrap($syndrome->nom,60)}} </a> </span> </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            @else
                <div>
                    <!-- On affiche un message expliquant qu'aucun syndrome n'a été trouvé -->
                    <em class="fs-5">{{__("textes.syndromes_index_aucun")}}</em>
                </div>
            @endif
        </div>
    </div>

    <!-- Ajout Select2 aux balises <select> -->
    <script>
        // Récupération des syndromes
        let donneesBrutes = {!! json_encode($syndromes) !!};

        let syndromes = [];
        for (const syndrome in donneesBrutes) {
            syndromes.push(donneesBrutes[syndrome].nom);
        }

        // Trie du tableau par ordre alphabétique
        syndromes.sort((a, b) => a.localeCompare(b));

        // Paramétrage de Select2
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
                selectOnClose: true,
                data: syndromes,
            });
        });
    </script>
</section>

@endsection
