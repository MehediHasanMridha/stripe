@extends('layouts.admin-template')
@section('title'){{ __("textes.actualitesAdmin_page_nom") }}@endsection
@section('content')
    <section>
        <div class="container-fluid container-xl">
            <!-- Si des données sur l'actualité existe -->
            @if(!empty($actualite))

                <div class="mb-3 mt-4">
                    <!-- div qui permet d'afficher les éléments sur la même ligne mais espacés -->
                    <div class="d-flex align-items-center justify-content-between">
                        <div></div>
                        <!-- Affiche dans un titre de niveau 3 le nom générique de l'ingrédient et son id -->
                        <h3> {{ $actualite->id}} - {{ $actualite->titre }}</h3>
                        <!-- Affiche une croix permettant de revenir a la page index -->
                        <a href="{{ route('actualitesAdmin.index') }}">
                            <button type="button" class="btn-close ms-2 text-border-space-right icon-size"
                                    aria-label="Close"></button>
                        </a>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('actualitesAdmin.edit', [$actualite->id]) }}"
                               class="btn btn-admin btn-color text-white me-4 fw-bold">{{ __("textes.actualitesAdmin_show_edit") }}</a>
                            <a href="{{ route('actualitesAdmin.traduction', [$actualite->id]) }}"
                               class="btn btn-admin btn-color text-white fw-bold me-4">{{ __("textes.actualitesAdmin_show_trad") }}</a>
                            <form action="{{ route('actualitesAdmin.destroy', [$actualite->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Êtes-vous sûr ?')" type="submit"
                                        class="btn btn-admin btn-color text-white me-4 fw-bold">{{ __("textes.actualitesAdmin_show_supp") }}</button>
                            </form>
                        </div>

                        <div class="col my-4">

                            <!-- Affichage de l'image -->
                            <div>
                                <h4>{{ __("textes.actualitesAdmin_show_image") }}</h4>
                                @if(!empty($actualite->image))
                                    <img src="{{ asset($actualite->image) }}" class="image-preview"
                                         alt="Image de l'actualité : {{ $actualite->titre }}">
                                @else
                                    <em>{{ __("textes.actualitesAdmin_show_image_none") }}</em>
                                @endif
                            </div>

                            <!-- Affichage des catégories -->
                            <div>
                                <h4>{{ __("textes.actualitesAdmin_index_tableau_colone_quatre") }}</h4>
                                <ul>
                                    @if(!empty($actualite->categories))
                                        @foreach($actualite->categories as $categorie)
                                            <li>{{ $categorie }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>

                            <!-- Affichage du paragraphe -->
                            <div>
                                <h4>{{ __("textes.actualitesAdmin_creat_paragraphe") }}</h4>
                                <p>
                                    @if($actualite->paragraphe)
                                        {!! html_entity_decode($actualite->paragraphe, ENT_HTML5, 'UTF-8') !!}
                                    @else
                                        {{ __("textes.actualitesAdmin_show_paragraphe_none") }}
                                    @endif
                                </p>
                            </div>

                            <!-- Affichage du résumé -->
                            <div>
                                <h4>{{ __("textes.actualitesAdmin_create_resume") }}</h4>
                                <p>
                                    @if($actualite->resume)
                                        {{ $actualite->resume }}
                                    @else
                                        {{ __("textes.actualitesAdmin_show_resume_none") }}
                                    @endif
                                </p>
                            </div>

                            <!-- Affichage du status -->
                            <div>
                                <h4>{{ __("textes.actualitesAdmin_show_status") }}</h4>
                                <div class="d-flex align-items-center">
                                    <div class="circle-status mb-1 me-1"
                                         style="background-color: {{ $actualite->status == 0 ? 'red' : 'green' }};"></div>
                                    <h5>{{ $actualite->status == 0 ? __("textes.actualitesAdmin_show_status_inactif") : __("textes.actualitesAdmin_show_status_actif") }}</h5>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Si des données sur l'actualité n'existe pas -->
            @else
                <div class="my-2 d-flex align-items-center">
                    <!-- Affiche une icone qui ramène l'utilisateur à la page contenant la liste de toutes les actualités -->
                    <a href="{{ route('actualitesAdmin.index')}}" class="mx-3">
                        <i class="fas fa-long-arrow-alt-left fs-2" style="color: black;"></i>
                    </a>
                    <!-- Texte annonçant qu'aucune donnée sur cette ingrédient trouvé n'a été trouvé -->
                    <em class="fs-5 ms-3">{{__("textes.actualitesAdmin_show_aucun")}}</em>
                </div>
            @endif
        </div>
    </section>
@endsection
