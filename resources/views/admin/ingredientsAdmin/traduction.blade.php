@extends('layouts.admin-template')
@section('title'){{__("textes.ingredientsAdmin_page_nom")}}@endsection

@section('content')
    <section>
        <div class="container-fluid container-xl">

            <div class="mb-3 mt-4">
                <div class="d-flex align-items-center justify-content-between phone-margin">
                    <div class="div-padding"></div>

                    <!-- Titre de niveau 2 -->
                    <h3 class="text-center text-create-size title-padding mx-3">{{__("textes.ingredientsAdmin_trad_ingredient")}} {{ $ingredient->nom }}</h3>

                    <!-- Affiche une icone qui ramène l'utilisateur en arrière -->
                    <button type="button" onclick="window.history.go(-1)"
                            class="btn-close ms-2 title-show-size text-border-space-right icon-size"
                            aria-label="Close"></button>
                </div>
            </div>

            <div class="container-fluid">
                <div>
                    <form action="{{ route('ingredientsAdmin.updateTraduction',[$ingredient->id]) }}" method="POST">

                        @csrf
                        @method('PATCH')

                        <div class="mb-3 ms-5 me-5">
                            <table class="w-100 text-show-size table">

                                <thead>
                                <tr class="border-bottom">
                                    <th class="w-100 form-label label-name edit-size"
                                        colspan="2">{{__("textes.ingredientsAdmin_trad_ingredient_traduction")}}</th>
                                    <th>
                                        <button onclick="return addRow('Ingredient')"
                                                class="btn btn-success text-center rounded-circle add-tr"><i
                                                class="fas fa-plus plus-size"></i></button>
                                    </th>
                                </tr>
                                </thead>

                                <tbody id="-Ingredient">
                                @foreach($references['nom'] as $langue_nom_ingredient => $traduction_nom_ingredient)
                                    <tr class="table-line">
                                        <td class="edit-size">{{ $langue_nom_ingredient }}</td>
                                        <td class="my-1"><input type="text" class="form-control edit-size"
                                                                name="editSymptome__{{$ingredient->id}}-{{$langue_nom_ingredient}}"
                                                                value="{{ $traduction_nom_ingredient }}"></td>
                                        <td>
                                            <button onclick="return removeRow('Ingredient')"
                                                    class="btn btn-danger text-center removeRowIngredient supp-tr"><i
                                                    class="fas fa-trash trash-size"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-3 ms-5 me-5">

                            @if(!empty($references['tropisme']))
                                <table class="w-100 text-show-size table">
                                    <thead>
                                    <tr class="border-bottom">
                                        <th class="w-100 form-label label-name edit-size"
                                            colspan="2">{{__("textes.ingredientsAdmin_trad_tropisme")}}</th>
                                        <th>
                                            <button onclick="return addRow('Tropisme')"
                                                    class="btn btn-success text-center rounded-circle add-tr"><i
                                                    class="fas fa-plus plus-size"></i></button>
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody id="-Tropisme">
                                    @foreach($references['tropisme'] as $langue_tropisme_ingredient => $traduction_tropsime_ingredient)
                                        <tr class="table-line">
                                            <td class="edit-size">{{ $langue_tropisme_ingredient }}</td>
                                            <td class="my-1"><input type="text" class="form-control edit-size"
                                                                    name="editSynonyme__{{$langue_tropisme_ingredient}}"
                                                                    value="{{ $traduction_tropsime_ingredient }}"></td>
                                            <td>
                                                <button onclick="return removeRow('Tropisme')"
                                                        class="btn btn-danger text-center removeRowTropisme supp-tr"><i
                                                        class="fas fa-trash trash-size"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-small-plus mt-3 ms-5 me-5"><span
                                class="text-white save-btn"> {{__("textes.ingredientsAdmin_trad_save")}} </span>
                        </button>
                    </form>
                </div>
            </div>

            <script type="text/javascript">
                let nbLineAdd = 0;
                let langues = @json($traductions_restantes);

                function addRow(table) {
                    nbLineAdd++;

                    let options;
                    Object.entries(langues).forEach(([iso, langue]) => {
                        options += "<option value=" + iso + ">" + langue + "</option>\n";
                    })

                    let tr;

                    tr = "<tr class='table-line'>" +
                        "<td>" +
                        "<select class='form-select edit-size' name='langue" + table + "_" + nbLineAdd + "'>" +
                        options +
                        "</select>" +
                        "</td>" +
                        "<td class='my-1'> <input type='text' class='edit-size form-control' name='add" + table + "_" + nbLineAdd + "'> </td>" +
                        "<td><button onclick=\"return removeRow('" + table + "')\" class='btn btn-danger text-center removeRow" + table + " supp-tr'><i class='fas fa-trash trash-size'></i></button></td>" +
                        "</tr>"


                    $('#-' + table).append(tr);

                    return false;
                }

                function removeRow(table) {
                    nbLineAdd--;

                    const classe = '.removeRow' + table;

                    $('#-' + table).on('click', classe, function () {
                        $(this).parent().parent().remove();
                    });

                    return false;
                }

            </script>
        </div>
    </section>
@endsection
