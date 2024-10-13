@extends('layouts.admin-template')
@section('title'){{__("textes.syndromes_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">
            <div class="mb-3 mt-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="div-padding"></div>

                    <!-- Titre de niveau 2 -->
                    <h2 class="text-center mb-2">{{ __("textes.syndromes_create_titre") }}</h2>

                    <!-- Affiche une icone qui ramène l'utilisateur en arrière -->
                    <button type="button" onclick="window.history.go(-1)"
                            class="btn-close ms-2 title-show-size text-border-space-right x-size"
                            aria-label="Close"></button>
                </div>
            </div>

            <div class="container">
                <div>
                    @if(session()->has('erreur'))
                        <div class="alert alert-danger">
                        <!--{{ session()->get('erreur') }}-->
                            Erreur : Le syndrome saisi existe déjà en base de données.
                        </div>
                    @endif
                    <datalist id="listeSymptomes">
                        @foreach($donnees_symptomes as $donnees_symptome)
                            <option value="{{ $donnees_symptome->id }}">{{ $donnees_symptome->traduction->text }}</option>
                        @endforeach
                    </datalist>
                    <!--Formulaire de création d'un symptome -->
                    <form action="{{ route('syndromesAdmin.store') }}" method="POST" enctype="multipart/form-data">

                        <!-- protection csrf -->
                    @csrf

                    <!-- Ajout d'un nom de nouveau symptome -->
                        <div class="mb-3">
                            <label for="nom"
                                   class="form-label label-name  edit-size">{{__("textes.syndromes_create_nom")}}</label>
                            <input type="text" class="form-control  edit-size" id="nom" aria-describedby="nomSyndromes"
                                   name="nouveauSyndrome"
                                   placeholder="{{__("textes.syndromes_create_nom_placeholder")}}">
                        </div>

                        <!-- Ajout d'une image d'un nouveau syndrome -->
                        <div class="mb-3">
                            <label for="nom"
                                   class="form-label label-name create-size">{{__("textes.syndromes_create_image")}}</label>
                            <div class="mb-3">
                                <input type="file" class="form-control @error('imageSyndrome') is-invalid @enderror"
                                       name="imageSyndrome" aria-label="imageSyndrome" onchange="loadImage(event)">
                                @error('imageSyndrome')
                                <div
                                    class="invalid-feedback">{{__("textes.syndromes_create_image_invalid_feedback")}}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col">
                                    <p>{{__("textes.syndromes_create_new_image")}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <img id="newImage" class="image-preview">
                                </div>
                            </div>
                        </div>

                        <!-- Mise en place des synonymes de ce symptome -->
                        <div class="mb-3">

                            <table class="w-100 text-show-size table">

                                <thead>
                                <tr class="border-bottom">
                                    <th class="w-100 form-label label-name  edit-size">{{__("textes.syndromes_create_symptomes")}}</th>
                                    <th>
                                        <button onclick="return addRow()"
                                                class="btn btn-success text-center rounded-circle add-tr"><i
                                                class="fas fa-plus plus-size"></i></button>
                                    </th>
                                </tr>
                                </thead>

                                <tbody id="-Synonyme">

                                <tr class="table-line">
                                    <td class="my-1"><input list='listeSymptomes' type="text"
                                                            class="edit-size form-control" name="addSymptome_1"
                                                            placeholder="{{__("textes.syndromes_create_symptomes_placeholder")}}">
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
                                        <button onclick="return removeRow()"
                                                class="btn btn-danger text-center removeRow supp-tr"><i
                                                class="fas fa-trash trash-size"></i></button>
                                    </td>
                                </tr>

                                </tbody>
                            </table>

                        </div>

                        <!-- Bouton submit qui envoie le formumaire dans la base de donnée et enregistre donc le nouveau symptome et ces synonymes -->
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-small-plus mt-3">
                                <span class="text-white save-btn">{{__("textes.syndromes_create_save")}}</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Script permettant d'ajouter ou supprimer des synonymes -->
            <script type="text/javascript">
                let nbLineAdd = 1;

                function addRow() {
                    nbLineAdd++;

                    //let tr = "<tr class='table-line'>"+
                    //                        "<td class='my-1'> <input type='text' class=' edit-size form-control' //name='addSynonyme_"+nbLineAdd+"'> </td>"+
                    //                    "<td><button onclick='return removeRow()' class='btn btn-danger text-center //removeRow supp-synonyme'><i class='fas fa-trash trash-size'></i></button></td>"//+
                    //                    "</tr>";

                    let tr = "<tr class='table-line'>" +
                        "<td class='my-1'> <input list='listeSymptomes' type='text' class=' edit-size form-control' name='addSymptome_" + nbLineAdd + "' placeholder='{{__("textes.syndromes_create_symptomes_placeholder")}}'> </td>" +
                        "<td class='my-1'><select class='form-select form-select-sm' name='addSymptomeScore_" + nbLineAdd + "'><option value='5'>5</option><option value='10'>10</option><option value='15'>15</option><option value='20'>20</option><option value='25'>25</option></select></td>" +
                        "<td><button onclick='return removeRow()' class='btn text-center removeRow supp-symptome'><i class='fas fa-trash trash-size'></i></button></td>" +
                        "</tr>"


                    $("tbody").append(tr);

                    return false;
                }

                function removeRow() {
                    nbLineAdd--;

                    $('tbody').on('click', '.removeRow', function () {
                        $(this).parent().parent().remove();
                    });

                    return false;
                }
            </script>
            <!-- Script permettant d'ajouter l'image d'un syndrome -->
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
    <style>
        .form-select-sm {
            width: 100px !important;
            height: 43px !important;
        }
    </style>
@endsection
