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
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_syndromes") }}</li>
                </ol>
            </nav>
        </div>

        <div class="container">

            <div class="d-flex align-items-center justify-content-center">
                <!-- Affiche le titre de la page -->
                <h2 class="text-center my-3">{{ __("textes.arianne_syndromes") }}</h2>
            </div>
            <div class="container phone-margin">
                <i>{{__("textes.expertise_gp_text")}}</i>
            </div>
            <!-- Créer un formulaire qui va permettre de chercher des syndromes dans la liste en appelant la page syndromes.search -->
            <form action="{{ route('syndromes.search') }}" class="phone-margin d-flex align-items-center mb-5"
                  method="GET">

                <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui être transmis par la suite au code -->
                <select class="select2 form-control me-2 list-search" name="search" id="search" aria-label="Search">
                    <option disabled selected value>{{__("textes.syndromes_placeholder_chercher_syndromes")}}</option>
                </select>
                <!-- Bouton affichant une icone et permettant de valider la recherche -->
                <button class="btn btn-small" type="submit"><i class="fas fa-search icon-size icon-white icon-size"></i>
                </button>

                <!-- Si l'utilisateur a effectué une recherche -->
            @if(request('search'))
                <!-- Affiche une icone qui ramène l'utilisateur vers la page contenant la liste de tous les syndromes -->
                    <a href="{{ route('syndromes.liste') }}">
                        <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                    </a>
                @endif

            </form>

            <!-- Si la liste des syndromes n'est pas vide -->
        @if(!empty($syndromes))

            <!-- Pour chaque syndrome récupéré du tableau syndromes et contenu dans syndrome -->
                @foreach($syndromes as $syndrome)
                    <div class="phone-margin d-flex align-items-center">
                    @if($syndrome->image)
                        <!-- On affiche une image -->
                            <img src="{{ asset($syndrome->image) }}"
                                 class="img-thumbnail circle me-5 image-list-width"
                                 alt="Image du syndrome de {{ $syndrome->nom }}"
                            >
                        @else
                            <div class="circle me-5">
                                <strong>{{ mb_substr($syndrome->nom, 0, 2, 'UTF-8') }}</strong>
                            </div>
                        @endif


                        <div class="d-flex flex-column">
                            <!-- On affiche le nom du syndrome qui amène l'utilisateur vers la page du syndrome -->
                            <a href="{{ route('syndromes.show', [$syndrome->id]) }}" class="fs-3 text-decoration-none">
                                <p class="list-element-name">{{ $syndrome->nom }}</p>
                            </a>

                            <!-- On affiche la future description du syndrome -->
                            <!--<p class="list-element-desc">Lorem ipsum dolor sit amet, consectetur?</p>-->
                        </div>

                    </div>
                    <!-- Ligne de séparation entre chaque syndrome -->
                    <hr class="list-element-separation">
                @endforeach

            <!-- Si la liste des syndromes est vide -->
            @else
                <div>
                    <!-- On affiche un message expliquant qu'aucun syndrome n'a été trouvé -->
                    <em class="fs-5">{{ __("textes.syndromes_liste_aucun") }}</em>
                </div>
        @endif
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
            $(document).ready(function () {
                $('.select2').select2({
                    theme: "bootstrap-5",
                    selectOnClose: true,
                    data: syndromes,
                });
            });
        </script>
    </section>
@endsection
