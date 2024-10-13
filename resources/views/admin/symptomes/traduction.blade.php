@extends('layouts.admin-template')
@section('title'){{__("textes.symptomes_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">
            <div class="mb-3 mt-4">
                <div class="d-flex align-items-center justify-content-between phone-margin">
                    <div class="div-padding"></div>

                    <!-- Titre de niveau 2 -->
                    <h3 class="text-center text-create-size title-padding mx-3">{{__("textes.symptomes_trad_symptome")}} {{ $symptome->traduction->text }}</h3>

                    <!-- Affiche une icone qui ramène l'utilisateur en arrière -->
                    <button type="button" onclick="window.history.go(-1)"
                            class="btn-close ms-2 title-show-size text-border-space-right icon-size"
                            aria-label="Close"></button>
                </div>
            </div>

            <div class="container">
                <div>
                    <form action="{{ route('symptomes.updateTraduction',[$symptome->id]) }}" method="POST" id="editform">

                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <table class="w-100 text-show-size table">

                                <thead>
                                <tr class="border-bottom">
                                    <th class="w-100 form-label label-name edit-size"
                                        colspan="2">{{__("textes.symptomes_trad_symptome_traduction")}}</th>
                                    <th>
                                        <button onclick="return addRow('Symptome')"
                                                class="btn btn-success text-center rounded-circle add-tr"><i
                                                class="fas fa-plus plus-size"></i></button>
                                    </th>
                                </tr>
                                </thead>

                                <tbody id="-Symptome">
                                @foreach($traductions_symptomes as $traduction_symptome)
                                    <tr class="table-line" id="Symptome{{$traduction_symptome->id}}">
                                        <td class="edit-size">{{ $traduction_symptome->lang }}</td>
                                        <td class="my-1"><input type="text" class="form-control edit-size"
                                                                name="editSymptome__{{$traduction_symptome->id}}-{{$traduction_symptome->lang}}"
                                                                value="{{ $traduction_symptome->text }}"></td>
                                        <td>
                                            <button onclick="return removeRow('Symptome{{$traduction_symptome->id}}')"
                                                    class="btn btn-danger text-center removeRowSymptome supp-tr"><i
                                                    class="fas fa-trash trash-size"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-3">

                            @if(!empty($traductions_synonymes))
                                @foreach($traductions_synonymes as $traductions)
                                    @foreach($traductions as $id_synonyme => $trad_synonyme)
                                        <table class="w-100 text-show-size table">
                                            <thead>
                                            <tr class="border-bottom">
                                                <th class="w-100 form-label label-name edit-size"
                                                    colspan="2">{{__("textes.symptomes_trad_synonyme")}}{{ $id_synonyme }}</th>
                                                <th>
                                                    <button onclick="return addRow('Synonyme{{$id_synonyme}}')"
                                                            class="btn btn-success text-center rounded-circle add-tr"><i
                                                            class="fas fa-plus plus-size"></i></button>
                                                </th>
                                            </tr>
                                            </thead>

                                            <tbody id="-Synonyme{{$id_synonyme}}">
                                            @foreach($trad_synonyme as $trad)
                                                <tr class="table-line" id="Synonyme{{$trad->id}}">
                                                    <td class="edit-size">{{ $trad->lang }}</td>
                                                    <td class="my-1"><input type="text" class="form-control edit-size"
                                                                            name="editSynonyme__{{$trad->id}}-{{$trad->lang}}"
                                                                            value="{{ $trad->text }}"></td>
                                                    <td>
                                                        <button onclick="return removeRow('Synonyme{{$trad->id}}')"
                                                                class="btn btn-danger text-center removeRowSynonyme{{$trad->id}} supp-tr">
                                                            <i class="fas fa-trash trash-size"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endforeach
                                @endforeach
                            @endif
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-small-plus mt-3">
                                    <span class="text-white save-btn">
                                        {{__("textes.symptomes_trad_save")}}
                                    </span>
                            </button>
                        </div>
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

                    tr = `<tr class='table-line' id='${table}_${nbLineAdd}'>` +
                        "<td>" +
                        `<select class='form-select edit-size' name='langue${table}_${nbLineAdd}'>` +
                        options +
                        "</select>" +
                        "</td>" +
                        "<td class='my-1'> <input type='text' class='edit-size form-control' name='add" + table + "_" + nbLineAdd + "'> </td>" +
                        "<td><button onclick=\"return removeRow('" + table + "_" + nbLineAdd + "')\" class='btn btn-danger text-center removeRow" + table + " supp-tr'><i class='fas fa-trash trash-size'></i></button></td>" +
                        "</tr>"


                    $('#-' + table).append(tr);

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
