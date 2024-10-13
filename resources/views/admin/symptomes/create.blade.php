@extends('layouts.admin-template')
@section('title'){{__("textes.symptomes_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">
            <div class="mb-3 mt-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="div-padding"></div>

                    <!-- Titre de niveau 2 -->
                    <h2 class="text-center mb-2">{{__("textes.symptomes_create_titre")}}</h2>

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
                            Erreur : Le symptôme saisi existe déjà en base de données.
                        </div>
                @endif
                <!--Formulaire de création d'un symptome -->
                    <form action="{{ route('symptomes.store') }}" method="POST">

                        <!-- protection csrf -->
                    @csrf

                    <!-- Ajout d'un nom de nouveau symptome -->
                        <div class="mb-3">
                            <label for="nom"
                                   class="form-label label-name  edit-size">{{__("textes.symptomes_create_ajout_nom")}}</label>
                            <input type="text" class="form-control  edit-size" id="nom" aria-describedby="nomSymptomes"
                                   name="nouveauSymptome"
                                   placeholder="{{__("textes.symptomes_create_symptomes_placeholder")}}">
                        </div>

                        <!-- Mise en place des synonymes de ce symptome -->
                        <div class="mb-3">

                            <table class="w-100 text-show-size table">

                                <thead>
                                <tr class="border-bottom">
                                    <th class="w-100 form-label label-name  edit-size">{{__("textes.symptomes_create_ajout_synonyme")}}</th>
                                    <th>
                                        <button onclick="return addRow()"
                                                class="btn btn-success text-center rounded-circle add-tr"><i
                                                class="fas fa-plus plus-size"></i></button>
                                    </th>
                                </tr>
                                </thead>

                                <tbody id="-Synonyme">

                                <tr class="table-line">
                                    <td class="my-1"><input type="text" class="edit-size form-control"
                                                            name="addSynonyme_1"
                                                            placeholder="{{__("textes.symptomes_create_ajout_synonyme_placeholder")}}">
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
                                    <span class="text-white save-btn">
                                        {{__("textes.symptomes_create_save")}}
                                    </span>
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

                    let tr = "<tr class='table-line'>" +
                        "<td class='my-1'> <input type='text' class=' edit-size form-control' name='addSynonyme_" + nbLineAdd + "' placeholder='{{__("textes.symptomes_create_ajout_synonyme_placeholder")}}'> </td>" +
                        "<td><button onclick='return removeRow()' class='btn btn-danger text-center removeRow supp-synonyme supp-tr'><i class='fas fa-trash trash-size'></i></button></td>" +
                        "</tr>";

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
        </div>
    </section>
@endsection
