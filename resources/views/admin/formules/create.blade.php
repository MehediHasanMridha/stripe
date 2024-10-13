@extends('layouts.admin-template')
@section('title'){{__("textes.formulesAdmin_page_nom")}}@endsection
@section('content')
    <section class="no-space-top">
        <div class="container-fluid container-xl">

            <div class="mb-3 mt-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="div-padding"></div>

                    <!-- Titre de niveau 2 -->
                    <h2 class="text-center mb-2">{{__("textes.formulesAdmin_create_titre")}}</h2>

                    <!-- Affiche une icone qui ramène l'utilisateur en arrière -->
                    <button type="button" onclick="window.history.go(-1)"
                            class="btn-close ms-2 title-show-size text-border-space-right x-size"
                            aria-label="Close"></button>
                </div>
            </div>

            <div class="conteiner-fluid">
                <div>
                    @if(session()->has('erreur'))
                        <div class="alert alert-danger">
                        <!--{{ session()->get('erreur') }}-->
                            {{__("textes.formulesAdmin_create_erreur_existe_deja")}}
                        </div>
                    @endif
                    <datalist id="listeSymptomes">
                        @foreach($donnees_symptomes as $donnees_symptome)
                            <option value="{{ $donnees_symptome->id }}">{{ $donnees_symptome->traduction->text }}</option>
                        @endforeach
                    </datalist>

                    <datalist id="listeIngredients">
                        @foreach($donnees_ingredients as $donnees_ingredient)
                            <option value="{{ $donnees_ingredient->id }}">{{$donnees_ingredient->nom}}</option>
                        @endforeach
                    </datalist>

                    <!--Formulaire de création d'un symptome -->
                    <form action="{{ route('formulesAdmin.store') }}" method="POST" enctype="multipart/form-data">

                        <!-- protection csrf -->
                    @csrf

                    <!-- Ajout d'un nom de nouveau symptome -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name  edit-size">{{__("textes.formulesAdmin_create_code")}}</label>
                            <input type="text" class="form-control  edit-size" id="code" aria-describedby="nomFormules"
                                   name="nouveauFormuleCode"
                                   placeholder="{{__("textes.formulesAdmin_create_code_placeholder")}}">
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name  edit-size">{{__("textes.formulesAdmin_create_nom")}}</label>
                            <input type="text" class="form-control  edit-size" id="nom" aria-describedby="nomFormules"
                                   name="nouveauFormule"
                                   placeholder="{{__("textes.formulesAdmin_create_nom_placeholder")}}">
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name  edit-size">{{__("textes.formulesAdmin_create_nom_chinois")}}</label>
                            <input type="text" class="form-control  edit-size" id="nom-chinois"
                                   aria-describedby="nomFormulesChinois" name="nouveauFormuleChinois"
                                   placeholder="{{__("textes.formulesAdmin_create_nom_chinois_placeholder")}}">
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="conseil"
                                   class="form-label label-name create-size">{{__("textes.formulesAdmin_create_conseil")}}</label>
                            <textarea type="text" class="form-control create-size" id="conseil" rows="3"
                                      aria-describedby="nouveauConseilFormule" name="nouveauConseilFormule"
                                      placeholder="{{__("textes.formulesAdmin_create_conseil_placeholder")}}"></textarea>
                            <script type="text/javascript">
                                newEditor('#conseil');
                            </script>
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="pharmacologie"
                                   class="form-label label-name create-size">{{__("textes.formulesAdmin_create_pharmacologie")}}</label>
                            <textarea type="text" class="form-control create-size" id="pharmacologie" rows="3"
                                      aria-describedby="nouveauPharmacologieFormule" name="nouveauPharmacologieFormule"
                                      placeholder="{{__("textes.formulesAdmin_create_pharmacologie_placeholder")}}"></textarea>
                            <script type="text/javascript">
                                newEditor('#pharmacologie');
                            </script>
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="toxicologie"
                                   class="form-label label-name create-size">{{__("textes.formulesAdmin_create_toxicologie")}}</label>
                            <textarea type="text" class="form-control create-size" id="toxicologie" rows="3"
                                      aria-describedby="nouveauToxicologieFormule" name="nouveauToxicologieFormule"
                                      placeholder="{{__("textes.formulesAdmin_create_toxicologie_placeholder")}}"></textarea>
                            <script type="text/javascript">
                                newEditor('#toxicologie');
                            </script>
                        </div>

                        <div class="mb-3 ms-5 me-5">
                            <label for="actions"
                                   class="form-label label-name create-size">{{__("textes.formulesAdmin_create_actions")}}</label>
                            <textarea type="text" class="form-control create-size" id="actions" rows="3"
                                      aria-describedby="nouveauActionsFormule" name="nouveauActionsFormule"
                                      placeholder="{{__("textes.formulesAdmin_create_actions_placeholder")}}"></textarea>
                            <script type="text/javascript">
                                newEditor('#actions');
                            </script>
                        </div>

                        <!-- Ajout d'une image d'un nouvel ingrédient -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name create-size">{{__("textes.formulesAdmin_create_image")}}</label>
                            <div class="mb-3">
                                <input type="file" class="form-control @error('imageIngredient') is-invalid @enderror"
                                       name="imageIngredient" aria-label="imageIngredient" onchange="loadImage(event)">
                                @error('imageFormules')
                                <div
                                    class="invalid-feedback">{{__("textes.formulesAdmin_create_image_invalid_feedback")}}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col">
                                    <p>{{__("textes.formulesAdmin_create_new_image")}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <img id="newImage" class="image-preview">
                                </div>
                            </div>
                        </div>

                        <!-- Mise en place des synonymes de ce symptome -->
                        <div class="mb-3 ms-5 me-5">

                            <table class="w-100 text-show-size table">

                                <thead>
                                <tr class="border-bottom">
                                    <th class="w-100 form-label label-name  edit-size">{{__("textes.formulesAdmin_create_symptomes")}}</th>
                                    <th>
                                        <button onclick="return addRow('Symptome')"
                                                class="btn btn-success text-center rounded-circle add-tr"><i
                                                class="fas fa-plus plus-size"></i></button>
                                    </th>
                                </tr>
                                </thead>

                                <tbody id="-Symptome">

                                <tr class="table-line">
                                    <td class="my-1"><input list='listeSymptomes' type="text"
                                                            class="edit-size form-control" name="addSymptome_1"
                                                            placeholder="{{__("textes.formulesAdmin_create_symptomes")}}">
                                    </td>
                                    <td class="my-1">


                                        <select name="addSymptomeScore_1" class="form-select form-select-sm"
                                                aria-label=".form-select-sm example">
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="15">15</option>
                                            <option value="20">20</option>
                                            <option value="25">25</option>
                                        </select>

                                    </td>
                                    <td>
                                        <button onclick="return removeRow('Symptome')"
                                                class="btn btn-danger text-center removeRowSymptome supp-tr"><i
                                                class="fas fa-trash trash-size"></i></button>
                                    </td>
                                </tr>

                                </tbody>
                            </table>

                        </div>


                        <div class="mb-3 ms-5 me-5">

                            <table class="w-100 text-show-size table">

                                <thead>
                                <tr class="border-bottom">
                                    <th class="w-100 form-label label-name  edit-size">{{__("textes.formulesAdmin_create_ingredients")}}</th>
                                    <th class="w-100 form-label label-name  edit-size">{{__("textes.formulesAdmin_create_ingredients_ponderations")}}</th>
                                    <th class="w-100 form-label label-name  edit-size">{{__("textes.formulesAdmin_create_ingredients_quantites")}}</th>
                                    <th>
                                        <button onclick="return addRowIngredient('Ingredient')"
                                                class="btn btn-success text-center rounded-circle add-tr"><i
                                                class="fas fa-plus plus-size"></i></button>
                                    </th>
                                </tr>
                                </thead>

                                <tbody id="-Ingredient">

                                <tr class="table-line">
                                    <td class="my-1"><input list='listeIngredients' type="text"
                                                            class="edit-size form-control" name="addIngredient_1"
                                                            placeholder="{{__("textes.formulesAdmin_create_ingredients_placeholder")}}">
                                    </td>
                                    <td class="my-1">

                                        <input type="number" name="addIngredientScore_1"
                                               aria-label=".form-select-sm example"
                                               placeholder="{{__("textes.formulesAdmin_create_ingredients_ponderations_placeholder")}}">

                                    </td>
                                    <td class="my-1">

                                        <input type="number" step="any" name="addIngredientQuantite_1"
                                               aria-label=".form-select-sm example"
                                               placeholder="{{__("textes.formulesAdmin_create_ingredients_quantites_placeholder")}}">

                                    </td>
                                    <td>
                                        <button onclick="return removeRow('Ingredient')"
                                                class="btn btn-danger text-center removeRowIngredient supp-tr"><i
                                                class="fas fa-trash trash-size"></i></button>
                                    </td>
                                </tr>

                                </tbody>
                            </table>

                        </div>


                        <!-- Bouton submit qui envoie le formumaire dans la base de donnée et enregistre donc le nouveau symptome et ces synonymes -->
                        <button type="submit" class="btn btn-small-plus mt-3 ms-5 me-5"><span
                                class="text-white save-btn">{{__("textes.ingredientsAdmin_create_save")}}</span>
                        </button>

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
            <!-- Script permettant d'ajouter ou supprimer des synonymes -->
            <script type="text/javascript">
                let nbLineAdd = 1;
                let nbLineIngredient = 1;

                function addRow(table) {
                    nbLineAdd++;

                    let tr;

                    tr = "<tr class='table-line'>" +
                        "<td class='my-1'> <input list='listeSymptomes' type='text' class=' edit-size form-control' name='add" + table + "_" + nbLineAdd + "' placeholder='{{__("textes.formulesAdmin_create_symptomes")}}'> </td>" +
                        "<td class='my-1'><select class='form-select form-select-sm' name='add" + table + "Score_" + nbLineAdd + "'><option value='5'>5</option><option value='10'>10</option><option value='15'>15</option><option value='20'>20</option><option value='25'>25</option></select></td>" +
                        "<td><button onclick=\"return removeRow('" + table + "')\" class='btn text-center removeRowSymptome supp-tr'><i class='fas fa-trash trash-size'></i></button></td>" +
                        "</tr>"

                    $('#-Symptome').append(tr);

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
