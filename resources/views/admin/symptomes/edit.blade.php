@extends('layouts.admin-template')
@section('title'){{__("textes.symptomes_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">

            <div class="mb-3 mt-4">
                <div class="d-flex align-items-center justify-content-between phone-margin">
                    <div class="div-padding"></div>

                    <!-- Titre de niveau 2 -->
                    <h3 class="text-center text-create-size title-padding mx-3">{{__("textes.symptomes_edit_symptome")}} {{ $symptome->traduction->text }}</h3>

                    <!-- Affiche une icone qui ramène l'utilisateur en arrière -->
                    <button type="button" onclick="window.history.go(-1)"
                            class="btn-close ms-2 title-show-size text-border-space-right icon-size"
                            aria-label="Close"></button>
                </div>
            </div>

            <div class="container">
                <div>
                    <form action="{{ route('symptomes.update',[$symptome->id]) }}" method="POST" id="editform">

                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="editConcordance"
                                   class="form-label label-name edit-size">{{__("textes.symptomes_edit_concordance")}}</label>
                            <input type="text"
                                   class="form-control edit-size"
                                   id="editConcordance"
                                   name="editConcordance"
                                   value="{{ $concordance?$concordance->traduction->text:"" }}">
                        </div>
                        <div class="container mb-3">
                            <div class="row">
                                <label for="nom"
                                       class="form-label label-name edit-size col-sm">{{__("textes.symptomes_edit_nom")}}</label>
                                <label for="concordance_symptome"
                                       class="form-label label-name edit-size col-sm text-center">Activer concordance</label>
                            </div>
                            <div class="row">
                                <input type="text"
                                       class="form-control edit-size col-sm"
                                       id="nom"
                                       name="editSymptome"
                                       value="{{ $symptome->traduction->text }}">
                                <input type="checkbox"
                                       class="col-sm"
                                       name="concordance_symptome"
                                       @if($symptome->concordant_activate)
                                       checked
                                    @endif
                                />
                            </div>

                        </div>
                        <div class="mb-3">

                            <table class="w-100 text-show-size table">

                                <thead>
                                <tr class="border-bottom">
                                    <th class="w-100 form-label label-name edit-size">{{__("textes.symptomes_edit_synonymes")}}</th>
                                    <th>
                                        <button onclick="return addRow('Synonyme')"
                                                class="btn btn-success text-center rounded-circle add-tr"><i
                                                class="fas fa-plus plus-size"></i></button>
                                    </th>
                                    <th>Activer concordance</th>
                                </tr>
                                </thead>

                                <tbody id="-Synonyme">
                                @foreach($synonymes as $synonyme)
                                    <tr class="table-line" id="{{$synonyme->id}}">
                                        <td class="my-1"><input type="text" class="form-control edit-size"
                                                                name="editSynonyme__{{$synonyme->id}}"
                                                                value="{{ $synonyme->traduction->text }}"
                                                                placeholder="{{__("textes.symptomes_create_ajout_synonyme_placeholder")}}">
                                        </td>
                                        <td>
                                            <button onclick="return removeRow('{{$synonyme->id}}')"
                                                    class="btn text-center removeRowSynonyme supp-tr"><i
                                                    class="fas fa-trash trash-size"></i></button>
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   name="concordant_{{$synonyme->id}}"
                                                   @if($synonyme->concordant_activate)
                                                       checked
                                                   @endif
                                                   />
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-small-plus mt-3">
                                    <span class="text-white save-btn">
                                        {{__("textes.symptomes_edit_btn_save")}}
                                    </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <script type="text/javascript">
                let nbLineAdd = 0;

                function addRow(table) {
                    nbLineAdd++;

                    let tr;

                    tr = "<tr class='table-line'>" +
                        "<td class='my-1'> <input type='text' class=' edit-size form-control' name='add" + table + "__add" + nbLineAdd + "' placeholder='{{__("textes.symptomes_create_ajout_synonyme_placeholder")}}'> </td>" +
                        "<td><button onclick=\"return removeRow('" + table + "')\" class='btn btn-danger text-center removeRowSynonyme supp-tr'><i class='fas fa-trash trash-size'></i></button></td>" +
                        "</tr>"


                    $('tbody').append(tr);

                    return false;
                }

                function removeRow(table) {
                    $('#'+table).remove();
                    $('#editform').append(`<input type='hidden' name='delete_${table.replace(/[^0-9\.]+/g, "")}'/>`);
                    return false;
                }

            </script>
        </div>
    </section>
@endsection
