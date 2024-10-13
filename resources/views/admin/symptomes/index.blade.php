@extends('layouts.admin-template')
@section('title'){{__("textes.symptomes_page_nom")}}@endsection
@push('title')
    <script type="text/javascript" src="{{ URL::asset('js/tri.js') }}"></script>
@endpush
@section('content')
    <section class="section">
        <!-- Titre de la page index -->
        <div class="index-name-div">
            <h1 class="index-name-text">{{__("textes.symptomes_index_titre")}}</h1>
        </div>

        <!-- Bouton +, dis " add " qui permet dajouter un symptome en renvoyant l'utilisateur sur la page create -->
        <div>
            <a class="btn btn-add rounded-circle" href="{{ route('symptomes.create') }}" role="button"> <i
                    class="fas fa-plus add-icon mt-3"> </i></a>
        </div>

        <div class="container">

            <!-- Créer un formulaire qui va permettre de chercher des symptomes dans la liste en appelant la foonction symptomes.search -->
            <form action="{{ route('symptomes.search') }}" class="d-flex align-items-center mb-5" method="GET">

                <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui être transmis par la suite au code -->
                <select class="select2 form-control me-2 list-search" name="search" id="search" aria-label="Search">
                    <option disabled selected value>{{__("textes.symptomes_chercher")}}</option>
                </select>
                <!-- Bouton affichant une icone et permettant de valider la recherche -->
                <button class="btn btn-small" type="submit"><i class="fas fa-search icon-size icon-white"></i></button>

                <!-- Si l'utilisateur a effectué une recherche -->
            @if(request('search'))
                <!-- Affiche une îcone qui ramène l'utilisateur vers la page contenant la liste de tous les symptomes -->
                    <a href="{{ route('symptomes.index') }}">
                        <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                    </a>
                @endif

            </form>

            @if(!empty($symptomes))

                <table class="w-100 table">
                    <!-- Titre des catégories du tableau -->
                    <thead class="w-100">
                    <tr>
                        <th class="liste-titre link-primary"
                            onclick="tri_tbody('trier',0,'int-reverse')">{{__("textes.symptomes_index_tableau_colone_une")}}</th>
                        <th class="liste-titre link-primary"
                            onclick="tri_tbody('trier',1)">{{__("textes.symptomes_index_tableau_colone_deux")}}</th>
                        <th class="liste-titre link-primary"
                            onclick="tri_tbody('trier',2)">{{__("textes.symptomes_index_tableau_colone_trois")}}</th>
                    </tr>
                    </thead>
                    <!-- Pour chaque symptomes récupéré du tableau symptomes et contenu dans symptome -->
                    <tbody id="trier">
                    @foreach($symptomes as $symptome)
                        <tr>
                            <!-- Affiche l'id du symptome -->
                            <th class="bord-color"><span class="liste-text"> {{ $symptome->id}} </span></th>
                            <!-- On affiche le nom de du symptome qui si cliqué amène l'utilisateur vers ce symptome -->
                            <td class="bord-color"><span class="liste-text"> <a
                                        href="{{ route("symptomes.show", [$symptome->id]) }}"
                                        class="liste-text"> {{wordwrap($symptome->traduction->text,60)}} </a> </span></td>

                            <td class="bord-color"> <span class="liste-text"> <a
                                        href="{{ route("symptomes.show", [$symptome->id]) }}" class="liste-text">
                            @foreach($symptome->synonymes as $key=>$synonyme)
                                            @if($synonyme ==  end($symptome->synonymes))
                                                {{wordwrap($synonyme->traduction->text,60)}}
                                            @else
                                                {{wordwrap($synonyme->traduction->text.($key+1===sizeof($symptome->synonymes)?"":" | "),60)}}
                                            @endif
                                        @endforeach
                        </a> </span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            @else
                <div>
                    <!-- On affiche un message expliquant qu'aucun symptome n'a été trouvé -->
                    <em class="fs-5">{{__("textes.symptomes_index_aucun")}}</em>
                </div>
            @endif
        </div>

        <!-- Ajout Select2 aux balises <select> -->
        <script>
            // Récupération des symptomes + synonymes
            var donneesBrutes = Object.values({!! json_encode($symptomes) !!});
            let symptomes = [];
            donneesBrutes.forEach((symptome) => {
                symptomes.push(symptome.traduction.text);
                console.log(symptome.synonymes)
                symptome.synonymes.forEach(synonyme => {
                    symptomes.push(synonyme.traduction.text);
                });
            });

            // Trie du tableau par ordre alphabétique
            symptomes.sort((a, b) => a.localeCompare(b));

            // Paramétrage de Select2
            $(document).ready(function () {
                $('.select2').select2({
                    theme: "bootstrap-5",
                    selectOnClose: true,
                    data: symptomes,
                });
            });
        </script>
    </section>
@endsection
