@extends('layouts.admin-template')
@section('title'){{ __("textes.actualitesAdmin_page_nom") }}@endsection
@section('content')
    <section class="no-space-top">
        <div class="mb-3 mt-4">
            <div class="d-flex align-items-center justify-content-between phone-margin">
                <div class="div-padding"></div>

                <!-- Titre de niveau 2 -->
                <h3 class="text-center text-create-size title-padding mx-3">{{ __("textes.actualitesAdmin_edit_titre") }}{{ $actualite->titre }}</h3>

                <!-- Affiche une icone qui ramène l'utilisateur en arrière -->
                <button type="button" onclick="window.history.go(-1)"
                        class="btn-close ms-2 title-show-size text-border-space-right icon-size"
                        aria-label="Close"></button>
            </div>
        </div>

        <div class="container-fluid container-xl">
            <form action="{{ route('actualitesAdmin.update', [$actualite->id]) }}" method="POST"
                  enctype="multipart/form-data">
                <!-- protection csrf -->
            @csrf
            @method('PATCH')

            <!-- Modification du titre de cette actualité -->
                <div class="mb-3 ms-5 me-5">
                    <label for="Titre"
                           class="form-label label-name edit-size">{{ __("textes.actualitesAdmin_edit_titre_article") }}
                        <span class="required">*</span></label>
                    <input type="text" name="Titre" id="Titre"
                           class="form-control edit-size @error('inputTitre') is-invalid @enderror"
                           value="{{ $actualite->titre }}">
                    @error('Titre')
                    <div class="invalid-feedback">
                        {{ $errors->first('Titre') }}
                    </div>
                    @enderror
                </div>

                <!-- Modification de l'image de cette actualité -->
                <div class="mb-3 ms-5 me-5">
                    <label for="imageActualite"
                           class="form-label label-name create-size">{{__("textes.ingredientsAdmin_create_image")}}</label>
                    <div class="mb-3">
                        <input type="file" class="form-control @error('imageActualite') is-invalid @enderror"
                               name="imageActualite" aria-label="imageActualite" onchange="loadImage(event)">
                        @error('imageActualite')
                        <div class="invalid-feedback">
                            {{ $errors->first('imageActualite') }}
                        </div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col">
                            <p>{{ __("textes.actualitesAdmin_edit_image") }}</p>
                        </div>
                        <div class="col">
                            <p>{{ __("textes.actualitesAdmin_edit_new_image") }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @if($actualite->image)
                                <img src="{{ asset($actualite->image) }}" class="image-preview"
                                     alt="Image de l'actualité : {{ $actualite->titre }}">
                            @else
                                <em>{{ __("textes.actualitesAdmin_edit_image_none") }}</em>
                            @endif
                        </div>
                        <div class="col">
                            <img id="newImage" class="image-preview">
                        </div>
                    </div>
                </div>

                <!-- Modification du/des catégorie(s) lié(s) à cette actualité -->
                <div class="mb-3 ms-5 me-5">
                    <div class="edit-size mb-3">{{ __("textes.actualitesAdmin_edit_categories") }}</div>
                    @foreach($all_categories as $categorie)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox{{$categorie->id}}"
                                   name="categories{{$categorie->id}}" value="{{ $categorie->id }}"
                                   @if(in_array($categorie->nom, (array)$actualite->categories)) checked @endif>
                            <label class="form-check-label"
                                   for="inlineCheckbox{{$categorie->id}}">{{ $categorie->nom }}</label>
                        </div>
                    @endforeach
                </div>

                <!-- Modification du résumé de cette actualité -->
                <div class="mb-3 ms-5 me-5">
                    <label for="inputResume"
                           class="form_label edit-size">{{ __("textes.actualitesAdmin_create_resume") }} <span
                            class="label-detail">{{ __("textes.actualitesAdmin_create_caract_min") }}</span></label>
                    <textarea class="form-control edit-size" name="inputResume" id="inputResume" rows="2"
                              maxlength="100" onkeypress="countWords(this)">{{ $actualite->resume }}</textarea>
                </div>

                <!-- Modification du paragraphe de cette actualité -->
                <div class="mb-3 ms-5 me-5">
                    <label for="inputParagraphe"
                           class="form-label edit-size">{{ __("textes.actualitesAdmin_index_titre") }}</label>
                    <!-- Texte enrichie -->
                    <textarea class="editor edit-size" name="inputParagraphe" id="inputParagraphe">
                            {!!  html_entity_decode($actualite->paragraphe, ENT_HTML5, 'UTF-8') !!}
                        </textarea>
                </div>

                <!-- Modification du status de cette actualité -->
                <div class="mb-3 ms-5 me-5">
                    <label for="nom"
                           class="form-label label-name create-size">{{ __("textes.actualitesAdmin_create_status") }}</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input check-style" name="statusActualite" type="checkbox"
                               id="statusActualite" @if($actualite->status == 1)checked @endif>
                        <label class="form-check-label check-size"
                               for="statusActualite">{{ __("textes.actualitesAdmin_create_actif") }}</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-small-plus mt-3 ms-5 me-5"><span
                        class="text-white save-btn"> {{ __("textes.actualitesAdmin_trad_save") }}</span></button>
            </form>
        </div>

        <!-- script ckeditor5 -->
        <script src="/plugins/ckeditor5/build/ckeditor.js"></script>
        <script>ClassicEditor
                .create(document.querySelector('.editor'), {

                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'link',
                            'bulletedList',
                            'numberedList',
                            '|',
                            'fontColor',
                            'fontBackgroundColor',
                            '|',
                            'outdent',
                            'indent',
                            '|',
                            'blockQuote',
                            'insertTable',
                            'mediaEmbed',
                            'undo',
                            'redo'
                        ]
                    },
                    language: 'fr',
                    image: {
                        toolbar: [
                            'imageTextAlternative',
                            'imageStyle:full',
                            'imageStyle:side'
                        ]
                    },
                    table: {
                        contentToolbar: [
                            'tableColumn',
                            'tableRow',
                            'mergeTableCells'
                        ]
                    },
                    licenseKey: '',
                })
                .then(editor => {
                    window.editor = editor;
                })
                .catch(error => {
                    console.error('Oups, une erreur s\'est produite !');
                    console.error('Veuillez signaler l’erreur suivante sur https://github.com/ckeditor/ckeditor5/issues avec l’identifiant de compilation et le tracé de la pile d’erreurs :');
                    console.warn('Build id: adlamaxygj03-c6qyv3wc8h');
                    console.error(error);
                });
        </script>

        <!-- script ajout de catégorie -->
        <script type="text/javascript">
            let nbLineAdd = 0;

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
