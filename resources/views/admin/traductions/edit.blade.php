@extends('layouts.admin-template')
@section('title'){{__("textes.traductions_page_nom")}}@endsection
@section('content')
    <section>
        <div class="container-fluid container-xl">

            <div class="d-flex align-items-center justify-content-between phone-margin">
                <div class="div-padding"></div>

                <h2 class="text-titre">{{ $infos['id'] }} - "{{ $infos['langue'] }}"</h2>

                <button type="button" onclick="window.location.href='{{ route('traductions.show', [$infos['id']]) }}'"
                        class="btn-close ms-2 title-show-size text-border-space-right icon-size"
                        aria-label="Close"></button>
            </div>

            <!-- Créer un formulaire qui va permettre de chercher des formules dans la liste en appelant la page formules.search -->
            <form action="{{ route('traductions.editSearch', [$infos['id'], $infos['exId']]) }}"
                  class="phone-margin d-flex my-5" method="GET">

                <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui être transmis par la suite au code -->
                <input class="form-control me-2 list-search" type="text" name="search"
                       placeholder="Chercher une clé ou un texte..."
                       @if(request('search')) value="{{ request('search') }}" @endif autocomplete="off"
                       aria-label="Search">
                <!-- Bouton affichant une icone et permettant de valider la recherche -->
                <button class="btn btn-small" type="submit"><i class="fas fa-search icon-size icon-white"></i></button>

                <!-- Si l'utilisateur a effectué une recherche -->
            @if(request('search'))
                <!-- Affiche une icone qui ramène l'utilisateur vers la page contenant la liste de tous les ingrédients -->
                    <a href="{{ route('traductions.edit', [$infos['id'], 1]) }}">
                        <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                    </a>
                @endif

            </form>

            <hr>

            <form action="{{ route('traductions.update', [$infos['id']]) }}" method="POST">
                @csrf
                @method('PATCH')
                <table class="table table-striped table-hover table-bordered text-size">
                    <thead>
                    <tr>
                        <th scope="col" class="th-legend th-size1">
                            <strong>{{__("textes.traductions_edit_soustitre_cle")}}</strong></th>
                        <th scope="col" class="th-legend th-size2">
                            <strong>{{__("textes.traductions_edit_soustitre_references")}}</strong></th>
                        <th scope="col" class="th-legend th-size3" colspan="2">
                            <strong>{{__("textes.traductions_edit_soustitre_texte")}}</strong></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th class="text-center fw-bold th-page" colspan="4" scope="row">
                            <h4>{{__("textes.traductions_edit_selectlangue")}}</h4></th>
                    </tr>
                    <tr>
                        <th scope="row"><label for="selectLangue">{{__("textes.traductions_edit_selectlangue")}}</label>
                        </th>
                        <td>
                            <select id="exemple" class="form-select" onchange="window.location.href = this.value">
                                @foreach($traductions_existantes as $traduction_existante)
                                    <option
                                        value="{{ route('traductions.edit', [$infos['id'], $traduction_existante->id]) }}"
                                        class="form-control"
                                        @if($infos['exId'] == $traduction_existante->id) selected @endif>{{ $traduction_existante->langue }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td colspan="2">
                            <select id="selectLangue" name="selectLangue" class="form-select" required>
                                @foreach($iso_639_1 as $iso => $nom)
                                    <option value="{{ $iso }}"
                                            @if($iso == $infos['langue']) selected @endif>{{ $nom }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @foreach($traduction as $pages => $textes)
                        <tr>
                            <th class="text-center fw-bold th-page w-100" colspan="3" scope="row"><h4>{{ $pages }}</h4>
                            </th>
                            <th>
                                <button onclick="return addRow('{{ $pages }}_')"
                                        class="btn btn-success d-flex text-center align-items-center rounded-circle add-icon"><i
                                        class="fas fa-plus plus-size"></i></button>
                            </th>
                        </tr>
                        @foreach($textes as $key => $texte)
                            <tr>
                                <th scope="row"><label
                                        for="input{{ $key }}">[@if(stristr($zoulou[$key], '#')){{ substr($zoulou[$key], 1) }}@else{{ $zoulou[$key] }}@endif
                                        ]</label></th>
                                <td><textarea class="form-control" readonly>{{ $exemple[$key] }}</textarea></td>
                                <td><textarea id="input{{ $key }}" name="{{ $key }}"
                                              class="form-control">{{ $texte }}</textarea></td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>

                <button type="submit"
                        class="btn btn-color btn-edit text-white me-3 p-2">{{__("textes.traductions_edit_btn_save")}}</button>
                <a href="{{ route('traductions.show', [$infos['id']]) }}" type="button"
                   class="btn btn-color btn-edit text-white mt-2 me-3 p-2">{{__("textes.traductions_edit_btn_annuler")}}</a>

                <div class="arrow-scroll">
                    <a><i class="fas fa-arrow-circle-up icon-color"></i></a>
                </div>
            </form>

            <script type="text/javascript">
                let nbLineAdd = 0;

                function addRow(modelKey) {
                    nbLineAdd++;

                    const tr = '<tr>' +
                        '<th scope="row">' + '<label for="' + modelKey + '_' + nbLineAdd + '">' + modelKey + '</label><input id="' + modelKey + '_' + nbLineAdd + '" name="' + modelKey + '_' + nbLineAdd + '" required><span style="color: red; vertical-align: top;">*</span></th>' +
                        '<td colspan="2"><textarea name="input' + modelKey + '_' + nbLineAdd + '" class="form-control"></textarea></td>' +
                        '<td><button onclick="return removeRow()" class="btn text-center text-white removeRow supp-icon"><i class="fas fa-trash trash-size"></i></button></td>' +
                        '</tr>';

                    $('tbody').append(tr);

                    return false;
                }

                function removeRow() {
                    nbLineAdd--;

                    $('tbody').on('click', '.removeRow', function () {
                        $(this).parent().parent().remove();
                    });

                    return false;
                }

                jQuery(function () {
                    $(function () {
                        $(window).scroll(function () {
                            if ($(this).scrollTop() > 4000) {
                                $('.arrow-scroll')
                                    .css('right', '30px')
                                    .click(function () {
                                        $('html, body').animate({scrollTop: -100}, 1);
                                    });
                            } else {
                                $('.arrow-scroll').removeAttr('style');
                            }
                        });
                    });
                });
            </script>
        </div>
    </section>
@endsection
