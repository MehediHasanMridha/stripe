@extends('layouts.admin-template')
@section('title'){{__("textes.syndromes_page_nom")}}@endsection

@section('content')
    <section>
        <div class="container-fluid container-xl">

            <div class="mb-3 mt-4">
                <div class="d-flex align-items-center justify-content-between phone-margin">
                    <div class="div-padding"></div>

                    <!-- Titre de niveau 2 -->
                    <h3 class="text-center text-create-size title-padding mx-3">{{ __("textes.syndromesAdmin_edit_titre") }} {{ $syndrome->nom }}</h3>

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

                    <form action="{{ route('syndromesAdmin.update',[$syndrome->id]) }}" method="POST"
                          enctype="multipart/form-data">

                        @csrf
                        @method('PATCH')

                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name edit-size">{{__("textes.syndromes_create_nom")}}</label>
                            <input type="text" class="form-control edit-size" id="nom" name="editSyndrome"
                                   value="{{ $syndrome->nom }}"
                                   placeholder="{{__("textes.syndromes_create_nom_placeholder")}}">
                        </div>

                        <!-- Modification de l'image -->
                        <div class="mb-3 ms-5 me-5">
                            <label for="nom"
                                   class="form-label label-name create-size">{{__("textes.syndromes_create_image")}}</label>
                            <div class="mb-3">
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                       name="imageSyndrome" aria-label="file example" onchange="loadImage(event)">
                                @error('image')
                                <div
                                    class="invalid-feedback">{{__("textes.syndromes_create_image_invalid_feedback")}}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col">
                                    <p>{{__("textes.syndromes_edit_image_actu")}} </p>
                                </div>
                                <div class="col">
                                    <p>{{__("textes.syndromes_edit_image_new")}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    @if($syndrome->image)
                                        <img src="{{ asset($syndrome->image) }}" class="image-preview"
                                             alt="Image de l'actualité : {{ $syndrome->nom }}">
                                    @else
                                        <em>{{__("textes.syndromes_edit_image_none")}}</em>
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
                                    <th class="w-100 form-label label-name edit-size">{{__("textes.syndromes_create_symptomes")}}</th>
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

                        <button type="submit" class="btn btn-small-plus mt-3 ms-5 me-5"><span
                                class="text-white save-btn">{{__("textes.syndromes_edit_save")}}</span></button>
                    </form>
                </div>
            </div>
            <script>
                // Récupération des symptomes + synonymes
                var donneesBrutes = Object.values({!! json_encode($donnees_symptomes) !!});

                let symptomes=donneesBrutes.map((symptome) => {
                    return{"text": symptome.traduction.text,"id":symptome.id};
                });
                // Trie du tableau par ordre alphabétique
                symptomes.sort((a, b) => a.text.localeCompare(b.text));

                function initTable(id){
                    $(`#${id}`).select2({
                        theme: "bootstrap-5",
                        placeholder:"{{__('textes.syndromes_create_symptomes_placeholder')}}",
                        data: symptomes,
                        allowClear: true
                    });
                }
                let nbLineAdd = 0;

                function addRow(table) {
                    nbLineAdd++;
                    let tr = "<tr class='table-line'>" +
                        "<td class='my-1'> <select class='select2 form-control' name='add" + table + "_" + nbLineAdd + "' id='select"+nbLineAdd+"' aria-label='Search'><option></option></select></td>"+
                        "<td class='my-1'><select class='form-select form-select-sm' name='add" + table + "Score_" + nbLineAdd + "'><option value='5'>5</option><option value='10'>10</option><option value='15'>15</option><option value='20'>20</option><option value='25'>25</option></select></td>" +
                        "<td><button onclick=\"return removeRow('" + table + "')\" class='btn text-center removeRowSymptome supp-tr'><i class='fas fa-trash trash-size'></i></button></td>" +
                        "</tr>"

                    $('tbody').append(tr);
                    initTable("select"+nbLineAdd);
                    return false;
                }

                function removeRow(table) {
                    nbLineAdd--;

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
    <style>
        .form-select-sm {
            width: 100px !important;
            height: 43px !important;
        }
    </style>
@endsection
