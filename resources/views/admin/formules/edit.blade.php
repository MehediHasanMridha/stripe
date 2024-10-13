@extends('layouts.admin-template')
@section('title'){{__("textes.formules_page_nom")}}@endsection
@section('content')
    <section>
        <div class="container-fluid container-xl">

            <div class="mb-3 mt-4">
                <div class="d-flex align-items-center justify-content-between phone-margin">
                    <div class="div-padding"></div>

                    <!-- Titre de niveau 2 -->
                    <h3 class="text-center text-create-size title-padding mx-3">{{__("textes.formulesAdmin_edit_titre")}} {{ $formule->nom_chinois }}</h3>

                    <!-- Affiche une icone qui ramène l'utilisateur en arrière -->
                    <button type="button" onclick="window.history.go(-1)"
                            class="btn-close ms-2 title-show-size text-border-space-right icon-size"
                            aria-label="Close"></button>
                </div>
            </div>

            <div class="container-fluid">
                <div>

                    <datalist id="listeSymptomes">
                        @foreach($donnees_symptomes as $donnees_symptome)
                            <option value="{{ $donnees_symptome->id }}">{{$donnees_symptome->traduction->text}}</option>
                        @endforeach
                    </datalist>

                    <datalist id="listeIngredients">
                        @foreach($donnees_ingredients as $donnees_ingredient)
                            <option value="{{ $donnees_ingredient->id }}">{{$donnees_ingredient->nom}}</option>
                        @endforeach
                    </datalist>

                    <form action="{{ route('formulesAdmin.update',[$formule->id]) }}" method="POST"
                          enctype="multipart/form-data">

                        @csrf
                        @method('PATCH')

                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name edit-size">{{__("textes.formulesAdmin_create_nom_chinois")}}</label>
                            <input type="text" class="form-control edit-size" id="nom-chinois" name="editFormuleChinois"
                                   placeholder="{{__("textes.formulesAdmin_create_nom_chinois_placeholder")}}"
                                   value="{{ $formule->nom_chinois }}">
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name edit-size">{{__("textes.formulesAdmin_create_nom")}}</label>
                            <input type="text" class="form-control edit-size" id="nom" name="editFormule"
                                   placeholder="{{__("textes.formulesAdmin_create_nom_placeholder")}}"
                                   value="{{ $formule->nom }}">
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name edit-size">{{__("textes.formulesAdmin_create_code")}}</label>
                            <input type="text" class="form-control edit-size" id="code" name="editFormuleCode"
                                   placeholder="{{__("textes.formulesAdmin_create_code_placeholder")}}"
                                   value="{{ $formule->code }}">
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="conseil"
                                   class="form-label label-name create-size">{{__("textes.formulesAdmin_create_conseil")}}</label>
                            <textarea type="text" class="form-control create-size" id="conseil" rows="3"
                                      aria-describedby="conseilFormule" name="conseilFormule"
                                      placeholder="{{__("textes.formulesAdmin_create_conseil_placeholder")}}">{{$formule->conseil}}</textarea>
                            <script type="text/javascript">
                                newEditor('#conseil');
                            </script>
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="pharmacologie"
                                   class="form-label label-name create-size">{{__("textes.formulesAdmin_create_pharmacologie")}}</label>
                            <textarea type="text" class="form-control create-size" id="pharmacologie" rows="3"
                                      aria-describedby="pharmacologieFormule" name="pharmacologieFormule"
                                      placeholder="{{__("textes.formulesAdmin_create_pharmacologie_placeholder")}}">{{$formule->pharmacologie}}</textarea>
                            <script type="text/javascript">
                                newEditor('#pharmacologie');
                            </script>
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="toxicologie"
                                   class="form-label label-name create-size">{{__("textes.formulesAdmin_create_toxicologie")}}</label>
                            <textarea type="text" class="form-control create-size" id="toxicologie" rows="3"
                                      aria-describedby="toxicologieFormule" name="toxicologieFormule"
                                      placeholder="{{__("textes.formulesAdmin_create_toxicologie_placeholder")}}">{{$formule->toxicologie}}</textarea>
                            <script type="text/javascript">
                                newEditor('#toxicologie');
                            </script>
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="actions"
                                   class="form-label label-name create-size">{{__("textes.formulesAdmin_create_actions")}}</label>
                            <textarea type="text" class="form-control create-size" id="actions" rows="3"
                                      aria-describedby="actionsFormule" name="actionsFormule"
                                      placeholder="{{__("textes.formulesAdmin_create_actions_placeholder")}}">{{$formule->actions}}</textarea>
                            <script type="text/javascript">
                                newEditor('#actions');
                            </script>
                        </div>

                        <!-- Modification d'une image d'un nouvel ingrédient -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name create-size">{{__("textes.formulesAdmin_create_image")}}</label>
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
                                    @if($formule->image)
                                        <img src="{{ asset($formule->image) }}" class="image-preview"
                                             alt="Image de l'actualité : {{ $formule->nom }}">
                                    @else
                                        <em>{{__("textes.ingredientsAdmin_edit_image_none")}}</em>
                                    @endif
                                </div>
                                <div class="col">
                                    <img id="newImage" class="image-preview">
                                </div>
                            </div>
                        </div>


                        <div class="mb-3 ms-5 me-5">
                            <table class="w-100 text-show-size table">
                                <thead>
                                <tr class="border-bottom">
                                    <th class="w-100 form-label label-name edit-size">{{__("textes.formulesAdmin_create_symptomes")}}</th>
                                    <th>
                                        <button onclick="return addRow('Symptome')"
                                                class="btn btn-success text-center rounded-circle add-tr"><i
                                                class="fas fa-plus plus-size"></i></button>
                                    </th>
                                </tr>
                                </thead>

                                <tbody id="-Symptome">
                                @foreach($symptomes as $symptome)
                                    <tr class="table-line">
                                        <td class="my-1"><input type="text" class="form-control edit-size"
                                                                name="editSymptome__{{$symptome->id}}"
                                                                value="{{ $symptome->traduction->text }}" readonly></td>
                                        <td class="my-1">

                                            <select name="editSymptomeScore__{{$symptome->id}}"
                                                    class="form-select form-select-sm"
                                                    aria-label=".form-select-sm example">
                                                <option selected>{{ $symptome->score }}</option>
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                                <option value="25">25</option>
                                            </select>

                                        </td>
                                        <td>
                                            <button onclick="return removeRow('Symptome')"
                                                    class="btn text-center removeRowSymptome supp-tr"><i
                                                    class="fas fa-trash trash-size"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                        <div class="mb-3 ms-5 me-5">
                            <table class="w-100 text-show-size table">
                                <thead>
                                <tr class="border-bottom">
                                    <th class="w-100 form-label label-name edit-size">{{__("textes.formulesAdmin_create_ingredients")}}</th>
                                    <th class="w-100 form-label label-name edit-size">{{__("textes.formulesAdmin_create_ingredients_ponderations")}}</th>
                                    <th class="w-100 form-label label-name edit-size">{{__("textes.formulesAdmin_create_ingredients_quantites")}}</th>
                                    <th>
                                        <button onclick="return addRowIngredient('Ingredient')"
                                                class="btn btn-success text-center rounded-circle add-tr"><i
                                                class="fas fa-plus plus-size"></i></button>
                                    </th>
                                </tr>
                                </thead>

                                <tbody id="-Ingredient">
                                @foreach($ingredients as $ingredient)
                                    <tr class="table-line">
                                        <td class="my-1"><input type="text" class="form-control edit-size"
                                                                name="editIngredient__{{$ingredient->id}}"
                                                                value="{{ $ingredient->nom }}" readonly></td>
                                        <td class="my-1">

                                            <input type="number" value="{{$ingredient->ponderation}}"
                                                   name="editIngredientScore__{{$ingredient->id}}"
                                                   aria-label=".form-select-sm example"
                                                   placeholder="{{__("textes.formulesAdmin_create_ingredients_ponderations_placeholder")}}">
                                        </td>
                                        <td class="my-1">
                                            <input type="number" step="any" value="{{$ingredient->quantite}}"
                                                   name="editIngredientQuantite__{{$ingredient->id}}"
                                                   aria-label=".form-select-sm example"
                                                   placeholder="{{__("textes.formulesAdmin_create_ingredients_quantites_placeholder")}}">
                                        </td>
                                        <td>
                                            <button onclick="return removeRow('Ingredient')"
                                                    class="btn text-center removeRowIngredient supp-tr"><i
                                                    class="fas fa-trash trash-size"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                        <button type="submit" class="btn btn-small-plus mt-3 ms-5 me-5"><span
                                class="text-white save-btn">{{__("textes.ingredientsAdmin_edit_save")}}</span></button>
                    </form>
                </div>
            </div>
            <style type="text/css">
                .form-select-sm {
                    width: 100px !important;
                    height: 43px !important;
                }

                #-Ingredient input {
                    height: 43px !important;
                }
            </style>
            <script type="text/javascript">
                let nbLineAdd = 0;
                let nbLineIngredient = 0;


                var donneesBrutes = Object.values({!! json_encode($donnees_symptomes) !!});

                let symptomes=donneesBrutes.map((symptome) => {
                    return{"text": symptome.traduction.text,"id":symptome.id};
                });
                // Trie du tableau par ordre alphabétique
                symptomes.sort((a, b) => a.text.localeCompare(b.text));

                function initTable(id){
                    $(`#${id}`).select2({
                        theme: "bootstrap-5",
                        placeholder:"{{__("textes.formulesAdmin_create_symptomes")}}",
                        data: symptomes,
                        allowClear: true
                    });
                }

                function addRow(table) {
                    nbLineAdd++;
                    let tr = "<tr class='table-line'>" +
                        "<td class='my-1'> <select class='select2 form-control' name='add" + table + "_" + nbLineAdd + "' id='select"+nbLineAdd+"' aria-label='Search'><option></option></select></td>"+
                        "<td class='my-1'><select class='form-select form-select-sm' name='add" + table + "Score_" + nbLineAdd + "'><option value='5'>5</option><option value='10'>10</option><option value='15'>15</option><option value='20'>20</option><option value='25'>25</option></select></td>" +
                        "<td><button onclick=\"return removeRow('" + table + "')\" class='btn text-center removeRowSymptome supp-tr'><i class='fas fa-trash trash-size'></i></button></td>" +
                        "</tr>"

                    $('#-Symptome').append(tr);
                    initTable("select"+nbLineAdd);
                    return false;
                }

                function addRowIngredient(table) {
                    nbLineIngredient++;

                    let tr;

                    tr = "<tr class='table-line'>" +
                        "<td class='my-1'> <input list='listeIngredients' type='text' class=' edit-size form-control' name='add" + table + "_" + nbLineIngredient + "' placeholder='{{__("textes.formulesAdmin_create_ingredients_placeholder")}}'> </td>" +
                        "<td class='my-1'><input type='number' id='tentacles' name='add" + table + "Score_" + nbLineIngredient + "' placeholder='{{__("textes.formulesAdmin_create_ingredients_ponderations_placeholder")}}'></td>" +
                        "<td class='my-1'><input type='number' step='any' id='tentacles' name='add" + table + "Quantite_" + nbLineIngredient + "' placeholder='{{__("textes.formulesAdmin_create_ingredients_quantites_placeholder")}}'></td>" +
                        "<td><button onclick=\"return removeRow('" + table + "')\" class='btn text-center removeRowIngredient supp-tr'><i class='fas fa-trash trash-size'></i></button></td>" +
                        "</tr>"

                    $('#-Ingredient').append(tr);

                    return false;
                }

                function removeRow(table) {
                    if (table == "Symptome") {
                        nbLineAdd--;
                    } else if (table == "Ingredient") {
                        nbLineIngredient--;
                    }


                    const classe = '.removeRow' + table;

                    $('tbody').on('click', classe, function () {
                        $(this).parent().parent().remove();
                    });

                    return false;
                }

            </script>

            <script type="text/javascript">
                let loadImage = function (event) {
                    const output = document.getElementById('newImage');
                    output.src = URL.createObjectURL(event.target.files[0]);
                    output.onload = function () {
                        URL.revokeObjectURL(output.src)
                    }
                };
            </script>

        </div>
    </section>
@endsection
