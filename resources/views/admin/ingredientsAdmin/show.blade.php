@extends('layouts.admin-template')
@section('title'){{__("textes.ingredientsAdmin_page_nom")}}@endsection
@section('content')
    <section>
        <div class="container-fluid container-xl">
            <!-- Si des données sur l'ingrédient existe -->
            @if(!empty($ingredient))
                <div class="mb-3 mt-4">
                    <!-- div qui permet d'afficher les éléments sur la même ligne mais espacés -->
                    <div class="d-flex align-items-center justify-content-between">
                        <div></div>
                        <!-- Affiche dans un titre de niveau 3 le nom générique de l'ingrédient et son id -->
                        <h3> {{ $ingredient->id}} - {{ $ingredient->nom }}</h3>
                        <!-- Affiche une croix permettant de revenir a la page index -->
                        <a href="{{ route('ingredientsAdmin.index') }}">
                            <button type="button" class="btn-close ms-2 show-margin-right x-size"
                                    aria-label="Close"></button>
                        </a>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">

                        <div class="d-flex align-items-center">
                            <!-- Affichage des boutons supprimer et editer -->
                            <a href="{{ route('ingredientsAdmin.edit', [$ingredient->id]) }}"
                               class="btn btn-admin btn-color text-white fw-bold me-4">{{__("textes.ingredientsAdmin_show_btn_edit")}}</a>
                        <!--<a href="{{ route('ingredientsAdmin.traduction', [$ingredient->id]) }}" class="btn btn-admin btn-color text-white fw-bold me-4">{{__("textes.ingredientsAdmin_show_traduction")}}</a>-->
                            <form action="{{ route('ingredientsAdmin.destroy', [$ingredient->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Êtes-vous sûr ?')"
                                        class="btn btn-admin btn-color text-white me-4 fw-bold">{{__("textes.ingredientsAdmin_show_btn_supp")}}</button>
                            </form>
                        </div>

                        <div class="col my-4">

                            <!-- Affichache du nom chinois -->
                            <div>
                                <h4>{{__("textes.ingredientsAdmin_show_nom_chinois")}}</h4>
                                @if (!empty($ingredient->nom_chinois))
                                    <h5>{{$ingredient->nom_chinois}}</h5>
                                @else
                                    <em class="fs-5 text-muted">Aucun</em>
                                @endif
                            </div>

                            <!-- Affichache du nom latin -->
                            <div>
                                <h4>{{__("textes.ingredientsAdmin_show_nom_latin")}}</h4>
                                @if (!empty($ingredient->nom_latin))
                                    <h5>{{$ingredient->nom_latin}}</h5>
                                @else
                                    <em class="fs-5 text-muted">Aucun</em>
                                @endif
                            </div>

                            <!-- Affichache de tropisme -->
                            <div>
                                <h4>{{__("textes.ingredientsAdmin_show_tropisme")}}</h4>
                                @if (!empty($ingredient->tropisme))
                                    <h5>{!! html_entity_decode($ingredient->tropisme, ENT_HTML5, 'UTF-8') !!}</h5>

                                @else
                                    <em class="fs-5 text-muted">{{__("textes.ingredientsAdmin_show_tropisme_none")}}</em>
                                @endif
                            </div>

                            <!-- Affichache de nature -->
                            <div>
                                <h4>{{__("textes.ingredientsAdmin_show_nature")}}</h4>
                                @if (!empty($ingredient->nature))
                                    <h5>{!! html_entity_decode($ingredient->nature, ENT_HTML5, 'UTF-8') !!}</h5>
                                @else
                                    <em class="fs-5 text-muted">{{__("textes.ingredientsAdmin_show_nature_none")}}</em>
                                @endif
                            </div>

                            <!-- Affichache de saveur -->
                            <div>
                                <h4>{{__("textes.ingredientsAdmin_show_saveur")}}</h4>
                                @if (!empty($ingredient->saveur))
                                    <h5>{!! html_entity_decode($ingredient->saveur, ENT_HTML5, 'UTF-8') !!}</h5>
                                @else
                                    <em class="fs-5 text-muted">{{__("textes.ingredientsAdmin_show_saveur_none")}}</em>
                                @endif
                            </div>

                            <!-- Affichache de action -->
                            <div>
                                <h4>{{__("textes.ingredientsAdmin_show_action")}}</h4>
                                @if (!empty($ingredient->action))
                                    <h5>{!! html_entity_decode($ingredient->action, ENT_HTML5, 'UTF-8') !!}</h5>
                                @else
                                    <em class="fs-5 text-muted">{{__("textes.ingredientsAdmin_show_action_none")}}</em>
                                @endif
                            </div>

                            <!-- Affichache de action -->
                            <div>
                                <h4>{{__("textes.ingredientsAdmin_show_partie")}}</h4>
                                @if (!empty($ingredient->partie))
                                    <h5>{!! html_entity_decode($ingredient->partie, ENT_HTML5, 'UTF-8') !!}</h5>
                                @else
                                    <em class="fs-5 text-muted">{{__("textes.ingredientsAdmin_show_partie_none")}}</em>
                                @endif
                            </div>

                            <!-- Affichage de l'image -->
                            <div>
                                <h4>{{__("textes.ingredientsAdmin_show_image")}}</h4>
                                @if (!empty($ingredient->image))
                                    <img src="{{asset($ingredient->image)}}" class="img-thumbnail thumbnail-width"
                                         alt="{{__("textes.ingredientsAdmin_show_image_alt")}}">
                                @else
                                    <em class="fs-5 text-muted">{{__("textes.ingredientsAdmin_show_image_none")}}</em>
                                @endif
                            </div>

                            <!-- Affichage du status de l'ingredient -->
                            <div>
                                <h4>{{__("textes.ingredientsAdmin_show_status")}}</h4>
                                <div class="d-flex align-items-center">
                                    <div class="circle-status mb-1 me-1"
                                         style="background-color: {{ $ingredient->status == 0 ? 'red' : 'green' }};"></div>
                                    <h5>{{ $ingredient->status == 0 ? __("textes.ingredientsAdmin_show_status_inactif") : __("textes.ingredientsAdmin_show_status_actif") }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Si des données sur l'ingrédient n'existe pas -->
            @else
                <div class="my-2 d-flex align-items-center">
                    <!-- Affiche une icone qui ramène l'utilisateur à la page contenant la liste de tous les ingrédients -->
                    <a href="{{ route('ingredientsAdmin.index')}}" class="mx-3">
                        <i class="fas fa-long-arrow-alt-left fs-2" style="color: black;"></i>
                    </a>
                    <!-- Texte annonçant qu'aucune donnée sur cette ingrédient trouvé n'a été trouvé -->
                    <em class="fs-5 ms-3">{{__("textes.ingredientsAdmin_show_aucun")}}</em>
                </div>
            @endif
        </div>
    </section>
@endsection
