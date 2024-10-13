@extends('layouts.admin-template')
@section('title'){{__("textes.actualitesAdmin_page_nom")}}@endsection
@section('content')
    <section>
        <div class="container-fluid container-xl">

            <div class="mb-3 mt-4">
                <div class="d-flex align-items-center justify-content-between phone-margin">
                    <div class="div-padding"></div>

                    <!-- Titre de niveau 2 -->
                    <h3 class="text-center text-create-size title-padding mx-3">{{__("textes.actualitesAdmin_trad_actualite")}}{{ $actualite->titre }}</h3>

                    <!-- Affiche une îcone qui ramène l'utilisateur en arrière -->
                    <button type="button" onclick="window.history.go(-1)"
                            class="btn-close ms-2 title-show-size text-border-space-right icon-size"
                            aria-label="Close"></button>
                </div>
            </div>

            <div class="container-fluid">
                <div>
                    <form action="{{ route('actualitesAdmin.updateTraduction',[$actualite->id]) }}" method="POST">

                        @csrf
                        @method('PATCH')

                        <div class="mb-3 ms-5 me-5">
                            <table class="w-100 text-show-size table">
                                <thead>
                                <tr class="border-bottom">
                                    <th class="w-100 form-label label-name edit-size"
                                        colspan="2">{{__("textes.actualitesAdmin_trad_actulaite_traduction")}}</th>
                                    <th>
                                        <button onclick="return addRow('Actualite')"
                                                class="btn btn-success text-center rounded-circle add-tr"><i
                                                class="fas fa-plus plus-size"></i></button>
                                    </th>
                                </tr>
                                </thead>

                                <tbody id="-Actualite">
                                @foreach($references['titre'] as $langue_actualite => $traduction_nom_actualite)
                                    <tr class="table-line">
                                        <td class="edit-size">{{ $langue_actualite }}</td>
                                        <td class="my-1"><input type="text" class="form-control edit-size"
                                                                name="editActualite__{{$actualite->id}}-{{$langue_actualite}}"
                                                                value="{{ $traduction_nom_actualite }}"></td>
                                        <td>
                                            <button onclick="return removeRow('Actualite')"
                                                    class="btn btn-danger text-center removeRowActualite supp-tr"><i
                                                    class="fas fa-trash trash-size"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!---------------
                         - PARAGRAPHE
                         --------------->

                        <div class="mb-3 ms-5 me-5">
                            @if(!empty($references['paragraphe']))
                                <table class="w-100 text-show-size table">
                                    <thead>
                                    <tr class="border-bottom">
                                        <th class="w-100 form-label label-name edit-size"
                                            colspan="2">{{__("textes.actualitesAdmin_trad_paragraphe")}}</th>
                                        <th>
                                            <button onclick="return addRow('Paragraphe')"
                                                    class="btn btn-success text-center rounded-circle add-tr"><i
                                                    class="fas fa-plus plus-size"></i></button>
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody id="-Paragraphe">
                                    @foreach($references['paragraphe'] as $langue_actualite => $traduction_paragraphe_actualite)
                                        <tr class="table-line">
                                            <td class="edit-size">{{ $langue_actualite }}</td>
                                            <td class="my-1">
                                                <textarea class="edit-size editor"
                                                          name="editParagraphe__{{$langue_actualite}}">{!!  html_entity_decode($traduction_paragraphe_actualite, ENT_HTML5, 'UTF-8') !!}</textarea>
                                            </td>
                                            <td>
                                                <button onclick="return removeRow('Paragraphe}')"
                                                        class="btn btn-danger text-center removeRowParagraphe supp-tr">
                                                    <i class="fas fa-trash trash-size"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <!---------------
                         - RESUME
                         --------------->

                        <div class="mb-3 ms-5 me-5">
                            @if(!empty($references['resume']))
                                <table class="w-100 text-show-size table">
                                    <thead>
                                    <tr class="border-bottom">
                                        <th class="w-100 form-label label-name edit-size"
                                            colspan="2">{{__("textes.actualitesAdmin_trad_resume")}}</th>
                                        <th>
                                            <button onclick="return addRow('Resume')"
                                                    class="btn btn-success text-center rounded-circle add-tr"><i
                                                    class="fas fa-plus plus-size"></i></button>
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody id="-Resume">
                                    @foreach($references['resume'] as $langue_actualite => $traduction_resume_actualite)
                                        <tr class="table-line">
                                            <td class="edit-size">{{ $langue_actualite }}</td>
                                            <td class="my-1"><textarea type="text" class="form-control edit-size"
                                                                       name="editResume__{{$langue_actualite}}">{{ $traduction_resume_actualite }}</textarea>
                                            </td>
                                            <td>
                                                <button onclick="return removeRow('Resume}')"
                                                        class="btn btn-danger text-center removeRowResume supp-tr"><i
                                                        class="fas fa-trash trash-size"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-small-plus mt-3 ms-5 me-5"><span
                                class="text-white save-btn"> {{__("textes.actualitesAdmin_trad_save")}} </span></button>
                    </form>
                </div>
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
                        console.error('Oops, something went wrong!');
                        console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
                        console.warn('Build id: adlamaxygj03-c6qyv3wc8h');
                        console.error(error);
                    });
            </script>
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

                    if (table === "Actualite") {
                        tr = "<tr class='table-line'>" +
                            "<td>" +
                            "<select class='form-select edit-size' name='langue" + table + "_" + nbLineAdd + "'>" +
                            options +
                            "</select>" +
                            "</td>" +
                            "<td class='my-1'> <input type='text' class='edit-size form-control' name='add" + table + "_" + nbLineAdd + "'> </td>" +
                            "<td><button onclick=\"return removeRow('" + table + "')\" class='btn btn-danger text-center removeRow" + table + " supp-tr'><i class='fas fa-trash trash-size'></i></button></td>" +
                            "</tr>"
                    } else if (table === "Paragraphe") {
                        tr = "<tr class='table-line'>" +
                            "<td>" +
                            "<select class='form-select edit-size' name='langue" + table + "_" + nbLineAdd + "'>" +
                            options +
                            "</select>" +
                            "</td>" +
                            "<td class='my-1'> <textarea rows='10' class='edit-size form-control editor" + nbLineAdd + "' name='add" + table + "_" + nbLineAdd + "'></textarea> </td>" +
                            "<td><button onclick=\"return removeRow('" + table + "')\" class='btn btn-danger text-center removeRow" + table + " supp-tr'><i class='fas fa-trash trash-size'></i></button></td>" +
                            "</tr>"
                    } else if (table === 'Resume') {
                        tr = "<tr class='table-line'>" +
                            "<td>" +
                            "<select class='form-select edit-size' name='langue" + table + "_" + nbLineAdd + "'>" +
                            options +
                            "</select>" +
                            "</td>" +
                            "<td class='my-1'> <textarea class='edit-size form-control' maxlength='100' name='add" + table + "_" + nbLineAdd + "'></textarea> </td>" +
                            "<td><button onclick=\"return removeRow('" + table + "')\" class='btn btn-danger text-center removeRow" + table + " supp-tr'><i class='fas fa-trash trash-size'></i></button></td>" +
                            "</tr>"
                    }

                    $('#-' + table).append(tr);

                    if (table === "Paragraphe") {
                        ClassicEditor
                            .create(document.querySelector(".editor" + nbLineAdd), {

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
                                console.error('Oops, something went wrong!');
                                console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
                                console.warn('Build id: adlamaxygj03-c6qyv3wc8h');
                                console.error(error);
                            });
                    }

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
