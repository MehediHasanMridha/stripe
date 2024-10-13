@extends('layouts.admin-template')
@section('title'){{__("textes.ingredientsAdmin_page_nom")}}@endsection
@section('content')
    <section>
        <div class="container-fluid container-xl">

            <div class="mb-3 mt-4">
                <div class="d-flex align-items-center justify-content-between phone-margin">
                    <div class="div-padding"></div>

                    <!-- Titre de niveau 2 -->
                    <h3 class="title-show-size">  {{__("textes.ingredientsAdmin_edit_titre")}} {{ $ingredient->id}}
                        - {{ $ingredient->nom }}</h3>

                    <!-- Affiche une îcone qui ramène l'utilisateur en arrière -->
                    <button type="button" onclick="window.history.go(-1)"
                            class="btn-close ms-2 title-show-size text-border-space-right icon-size"
                            aria-label="Close"></button>
                </div>
            </div>

            <div class="container-fluid">
                <div>

                    <!-- formulaire de modification d'un ingrédient -->
                    <form action="{{ route('ingredientsAdmin.update', [$ingredient->id]) }}" method="POST"
                          enctype="multipart/form-data">

                        <!-- protection csrf -->
                    @csrf
                    @method('PATCH')

                    <!-- Modification d'un nom d'un nouvel ingrédient -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name create-size">{{__("textes.ingredientsAdmin_create_ajout_nom_fr")}}</label>
                            <input type="text" class="form-control create-size" id="nom"
                                   aria-describedby="nomIngredients" name="nouvelIngredient"
                                   placeholder="{{__("textes.ingredientsAdmin_create_ajout_nom_placehoder")}}"
                                   value="{{ $ingredient->nom }}">
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name create-size">{{__("textes.ingredientsAdmin_show_nom_chinois")}}</label>
                            <input type="text" class="form-control create-size" id="nom"
                                   aria-describedby="nomIngredientsChinois" name="nouvelIngredientChinois"
                                   placeholder="{{__("textes.ingredientsAdmin_create_ajout_nom_placehoder")}}"
                                   value="{{ $ingredient->nom_chinois }}">
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name create-size">{{__("textes.ingredientsAdmin_show_nom_latin")}}</label>
                            <input type="text" class="form-control create-size" id="nom"
                                   aria-describedby="nomIngredientsLatin" name="nouvelIngredientLatin"
                                   placeholder="{{__("textes.ingredientsAdmin_create_ajout_nom_placehoder")}}"
                                   value="{{ $ingredient->nom_latin }}">
                        </div>

                        <!-- Modification d'une description d'un nouvel ingrédient -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="tropisme"
                                   class="form-label label-name create-size">{{__("textes.ingredientsAdmin_create_tropisme")}}</label>
                            <textarea type="text" class="form-control create-size" id="tropisme" rows="3"
                                      aria-describedby="tropismeIngredient" name="tropismeIngredient"
                                      placeholder="{{__("textes.ingredientsAdmin_create_tropisme_placeholder")}}">{{$ingredient->tropisme}}</textarea>
                            <script type="text/javascript">
                                newEditor('#tropisme');
                            </script>
                        </div>

                        <!-- nature -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="nature"
                                   class="form-label label-name create-size">{{__("textes.ingredientsAdmin_create_nature")}}</label>
                            <textarea type="text" class="form-control create-size" id="nature" rows="3"
                                      aria-describedby="natureIngredient" name="natureIngredient"
                                      placeholder="{{__("textes.ingredientsAdmin_create_nature_placeholder")}}">{{$ingredient->nature}}</textarea>
                            <script type="text/javascript">
                                newEditor('#nature');
                            </script>
                        </div>

                        <!-- saveur -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="saveur"
                                   class="form-label label-name create-size">{{__("textes.ingredientsAdmin_create_saveur")}}</label>
                            <textarea type="text" class="form-control create-size" id="saveur" rows="3"
                                      aria-describedby="saveurIngredient" name="saveurIngredient"
                                      placeholder="{{__("textes.ingredientsAdmin_create_saveur_placeholder")}}">{{$ingredient->saveur}}</textarea>
                            <script type="text/javascript">
                                newEditor('#saveur');
                            </script>
                        </div>

                        <!-- action -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="action"
                                   class="form-label label-name create-size">{{__("textes.ingredientsAdmin_create_action")}}</label>
                            <textarea type="text" class="form-control create-size" id="action" rows="3"
                                      aria-describedby="actionIngredient" name="actionIngredient"
                                      placeholder="{{__("textes.ingredientsAdmin_create_action_placeholder")}}">{{$ingredient->action}}</textarea>
                            <script type="text/javascript">
                                newEditor('#action');
                            </script>
                        </div>

                        <!-- partie -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="partie"
                                   class="form-label label-name create-size">{{__("textes.ingredientsAdmin_create_partie")}}</label>
                            <textarea type="text" class="form-control create-size" id="partie" rows="3"
                                      aria-describedby="partieIngredient" name="partieIngredient"
                                      placeholder="{{__("textes.ingredientsAdmin_create_partie_placeholder")}}">{{$ingredient->partie}}</textarea>
                            <script type="text/javascript">
                                newEditor('#partie');
                            </script>
                        </div>

                        <!-- Modification d'une image d'un nouvel ingrédient -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name create-size">{{__("textes.ingredientsAdmin_create_image")}}</label>
                            <div class="mb-3">
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                       name="imageIngredient" aria-label="file example" onchange="loadImage(event)">
                                @error('image')
                                <div
                                    class="invalid-feedback">{{__("textes.ingredientsAdmin_create_image_invalid_feedback")}}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col">
                                    <p>{{__("textes.ingredientsAdmin_edit_image_actu")}} </p>
                                </div>
                                <div class="col">
                                    <p>{{__("textes.ingredientsAdmin_edit_image_new")}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    @if($ingredient->image)
                                        <img src="{{ asset($ingredient->image) }}" class="image-preview"
                                             alt="Image de l'actualité : {{ $ingredient->nom }}">
                                    @else
                                        <em>{{__("textes.ingredientsAdmin_edit_image_none")}}</em>
                                    @endif
                                </div>
                                <div class="col">
                                    <img id="newImage" class="image-preview">
                                </div>
                            </div>
                        </div>

                        <!-- Modification d'un status d'un nouvel ingrédient -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name create-size">{{__("textes.ingredientsAdmin_create_status")}}</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input check-style" name="statusIngredient" type="checkbox"
                                       id="statusIngredient" @if($ingredient->status==1)checked @endif>
                                <label class="form-check-label check-size"
                                       for="statusIngredient">{{__("textes.ingredientsAdmin_create_status_switch")}}</label>
                            </div>
                        </div>

                        <!-- bouton submit qui envoie le formulaire dans la base de donnée et enregistre dong les modification sur l'ingrédient-->
                        <button type="submit" class="btn btn-small-plus mt-3 ms-5 me-5"><span
                                class="text-white save-btn">{{__("textes.ingredientsAdmin_create_save")}}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            let loadImage = function (event) {
                const output = document.getElementById('newImage');
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function () {
                    URL.revokeObjectURL(output.src)
                }
            };
        </script>
    </section>
@endsection
