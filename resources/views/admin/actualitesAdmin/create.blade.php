@extends('layouts.admin-template')
@section('title') {{ __("textes.actualitesAdmin_page_nom") }}@endsection
@section('content')
    <section>
        <div class="container-fluid container-xl">
            <div class="mb-3 mt-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="div-padding"></div>

                    <!-- Titre de niveau 2 -->
                    <h2 class="text-center text-create-size title-padding mb-2"> {{ __("textes.actualitesAdmin_create_titre") }}</h2>

                    <!-- Affiche une icone qui ramène l'utilisateur en arrière -->
                    <button type="button" onclick="window.history.go(-1)"
                            class="btn-close ms-2 text-border-space-right x-size" aria-label="Close"></button>
                </div>
            </div>

            <div class="container-fluid">
                <div>
                    <!-- formulaire de creations à la nouvelle actualité -->
                    <form action="{{ route('actualitesAdmin.store') }}" method="POST" enctype="multipart/form-data">

                        <!-- protection csrf -->
                    @csrf

                    <!-- Ajout d'un titre à la nouvelle actualité -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="Titre"
                                   class="form-label label-name create-size">{{ __("textes.actualitesAdmin_index_tableau_colone_deux") }}
                                <span class="required">*</span></label>
                            <input type="text" class="form-control create-size" id="Titre" name="Titre"
                                   placeholder="Rentrer le titre de la nouvelle actualité...">
                        </div>

                        <!-- Ajout d'une image à la nouvelle actualité -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="imageActualite"
                                   class="form-label label-name create-size">{{ __("textes.actualitesAdmin_create_image") }}</label>
                            <div class="mb-3">
                                <input type="file" class="form-control @error('imageActualite') is-invalid @enderror"
                                       name="imageActualite" onchange="loadImage(event)">
                                @error('imageActualite')
                                <div class="invalid-feedback">
                                    {{ $errors->first('imageActualite') }}
                                </div>
                                @enderror
                            </div>

                            <div>
                                <p>{{ __("textes.actualitesAdmin_create_image_visu") }}</p>
                                <img id="newImage" class="image-preview">
                            </div>
                        </div>

                        <!-- Ajout de catégorie(s) à la nouvelle actualité -->
                        <div class="mb-3 ms-5 me-5">
                            <div
                                class="create-size mb-3">{{ __("textes.actualitesAdmin_index_tableau_colone_quatre") }}</div>
                            @foreach($all_categories as $categorie)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox"
                                           id="inlineCheckbox{{$categorie->id}}" name="categories{{$categorie->id}}"
                                           value="{{ $categorie->id }}">
                                    <label class="form-check-label"
                                           for="inlineCheckbox{{$categorie->id}}">{{ $categorie->nom }}</label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Ajout du résumé à la nouvelle actualité -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="Resume"
                                   class="form-label label-name create-size">{{ __("textes.actualitesAdmin_create_resume") }}
                                <span
                                    class="label-detail">{{ __("textes.actualitesAdmin_create_caract_min") }}</span></label>
                            <textarea type="text" class="form-control create-size" id="Resume" name="Resume" rows="2"
                                      maxlength="100"
                                      placeholder="{{ __("textes.actualitesAdmin_create_placeholder_resume") }}"></textarea>
                        </div>

                        <!-- Ajout du paragraphe à la nouvelle actualité -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="Paragraphe"
                                   class="form-label label-name create-size">{{ __("textes.actualitesAdmin_creat_paragraphe") }}</label>
                            <textarea type="text" class="form-control create-size" id="Paragraphe" rows="30"
                                      name="Paragraphe"
                                      placeholder="{{ __("textes.actualitesAdmin_create_placeholder_paragraphe") }}"></textarea>
                        </div>

                        <!-- Ajout d'un status à la nouvelle actualité -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name create-size">{{ __("textes.actualitesAdmin_create_status") }}</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input check-style" name="statusActualite" type="checkbox"
                                       id="statusActualite" checked>
                                <label class="form-check-label check-size"
                                       for="statusActualite">{{ __("textes.actualitesAdmin_create_actif") }}</label>
                            </div>
                        </div>

                        <!-- bouton submit qui envoie le formulaire dans la base de donnée et enregistre donc la nouvelle actualité-->
                        <button type="submit" class="btn btn-small-plus mt-3 ms-5 me-5"><span
                                class="text-white save-btn">{{ __("textes.actualitesAdmin_create_save") }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            let nbLineAdd = 1;

            function addRow() {
                nbLineAdd++;

                const tr = "<tr class='table-line'>" +
                    "<td class='my-1'> <input type='text' class='form-control edit-size' name='add_" + nbLineAdd + "'> </td>" +
                    "<td><button onclick='return removeRow()' class='btn text-center removeRowCategorie supp-tr'><i class='fas fa-trash trash-size'></i></button></td>" +
                    "</tr>";

                $('tbody').append(tr);

                return false;
            }

            function removeRow() {
                nbLineAdd--;

                $('tbody').on('click', '.removeRowCategorie', function () {
                    $(this).parent().parent().remove();
                });

                return false;
            }

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
