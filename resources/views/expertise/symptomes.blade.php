@extends('layouts.template')
@section('title'){{__("textes.expertise_page_nom")}}@endsection
@section('content')
    <section>
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a class="arianne-link"
                                                                              href="{{ route('expertise.index') }}">{{ __("textes.arianne_expertise") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_symptomes") }}</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex align-items-center justify-content-center">
            <!-- Affiche le titre de la page -->
            <h2 class="text-center my-3">{{ __("textes.expertise_index_titre") }}</h2>
        </div>
        <div class="container phone-margin rappels-juridiques mb-3">
            <li><strong> {{__("textes.expertise_but_informatif")}} </strong></li>
            @role('praticien|admin')
                <strong> {{__("textes.expertise_info1")}}</strong> {{__("textes.expertise_info2")}}
                <ul>
                    <li>
                        <strong> {{__("textes.expertise_info3")}} </strong> {{__("textes.expertise_info4")}} <span class="text-danger">{{__("textes.expertise_info5")}}</span>
                    </li>
                    <li>{{__("textes.expertise_info6")}}</li>
                </ul>
                {{__("textes.expertise_info7")}}
            @endrole
        </div>
        <div class="container">
            <div class="phone-margin">
                <form action="{{ route('expertise.show') }}" method="GET">

                    <script type="text/javascript">
                        function check_checkbox() {
                            let checkbox = document.getElementById("single");
                            return checkbox.checked;
                        }

                        function hide_or_show() {
                            let items = document.getElementsByClassName("hide-show");
                            for (let item of items) {
                                if (check_checkbox())
                                    item.classList.add("d-none");
                                else
                                    item.classList.remove("d-none");
                            }

                            // Inverse
                            let itemsReverse = document.getElementsByClassName("hide-show-reverse");
                            for (let item of itemsReverse) {
                                if (check_checkbox())
                                    item.classList.remove("d-none");
                                else
                                    item.classList.add("d-none");
                            }
                        }
                    </script>
                    @level(3)
                    <input
                        type="checkbox"
                        name="single"
                        id="single"
                        autocomplete="off"
                        onchange="hide_or_show()"
                    />
                    <span>{{__("textes.expertise_symptomes_mode_simple")}}</span>
                    @else
                        <input type="hidden" name="single" id="single" value="true"/>
                        @endlevel
                        <!-- Token CSRF de sécurité -->
                    @csrf

                    <!----------------------
                        - SYMPTOME 1
                        ----------------------->
                        <div class="row">
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="symptome1"
                                           class="form-label"><strong>{{__("textes.expertise_symptomes_premier")}}</strong>
                                        <span style="color: red; vertical-align: top"><em>*</em></span>
                                    </label>
                                    <i class="btn fas fa-info-circle d-block d-xxl-none"
                                       data-toggle="popover"
                                       data-bs-placement="right"
                                       title="Information"
                                       data-content="{{__("textes.expertise_symptomes_premier_info")}}">
                                    </i>
                                </div>

                                <select class="select2 form-control @error('symptome1') is-invalid @enderror" name="symptome1" id="symptome1">
                                </select>
                                <input type="hidden" class="form-control ponderation-number" id="ponderation1"
                                       name="ponderation1" value="{{ $ponderations[0] }}">

                                @error('symptome1')
                                <div class="invalid-feedback">
                                    {{ $errors->first('symptome1') }}
                                </div>
                                @enderror


                            </div>

                            <div class="col d-none d-xxl-block">
                                {{__("textes.expertise_symptomes_premier_info")}}
                            </div>
                        </div>

                        <!-- Ligne de séparation entre les 2 champs -->
                        <hr class="input-separation hide-show">
                        @level(3)

                        <!----------------------
                        - SYMPTOME 2
                        ----------------------->
                        <div class="row hide-show">
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="symptome2"
                                           class="form-label "><strong>{{__("textes.expertise_symptomes_deuxieme")}}</strong>
                                        <span style="color: red; vertical-align: top"><em>*</em></span>
                                    </label>
                                    <i class="btn fas fa-info-circle d-block d-xxl-none"
                                       data-toggle="popover"
                                       data-bs-placement="right"
                                       title="Information"
                                       data-content="{{__("textes.expertise_symptomes_deuxieme_info")}}">
                                    </i>
                                </div>

                                <select class="select2 form-control @error('symptome2') is-invalid @enderror"
                                        name="symptome2" id="symptome2">
                                </select>
                                <input type="hidden" class="form-control ponderation-number" id="ponderation2"
                                       name="ponderation2" value="{{ $ponderations[1] }}">
                                @error('symptome2')
                                <div class="invalid-feedback">
                                    {{ $errors->first('symptome2') }}
                                </div>
                                @enderror
                            </div>

                            <div class="col d-none d-xxl-block">
                                {{__("textes.expertise_symptomes_deuxieme_info")}}
                            </div>
                        </div>

                        <!-- Ligne de séparation entre les 2 champs -->
                        <hr class="input-separation hide-show">


                        <!----------------------
                        - SYMPTOME 3
                        ----------------------->
                        <div class="row hide-show">
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="symptome3"
                                           class="form-label"><strong>{{__("textes.expertise_symptomes_troisieme")}}</strong>
                                        <span style="color: red; vertical-align: top"><em>*</em></span>
                                    </label>
                                    <i class="btn fas fa-info-circle d-block d-xxl-none"
                                       data-toggle="popover"
                                       data-bs-placement="right"
                                       title="Information"
                                       data-content="{{__("textes.expertise_symptomes_troisieme_info")}}">
                                    </i>
                                </div>

                                <select class="select2 form-control @error('symptome3') is-invalid @enderror"
                                        name="symptome3" id="symptome3">
                                </select>
                                <input type="hidden" class="form-control ponderation-number" id="ponderation3"
                                       name="ponderation3" value="{{ $ponderations[2] }}">
                                @error('symptome3')
                                <div class="invalid-feedback">
                                    {{ $errors->first('symptome3') }}
                                </div>
                                @enderror
                            </div>

                            <div class="col d-none d-xxl-block">
                                {{__("textes.expertise_symptomes_troisieme_info")}}
                            </div>
                        </div>

                        <!-- Ligne de séparation entre les 2 champs -->
                        <hr class="input-separation hide-show">


                        <!----------------------
                        - SYMPTOME 4
                        ----------------------->
                        <div class="row hide-show">
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="symptome4"
                                           class="form-label "><strong>{{__("textes.expertise_symptomes_quatrieme")}}</strong>
                                    </label>
                                    <i class="btn fas fa-info-circle d-block d-xxl-none"
                                       data-toggle="popover"
                                       data-bs-placement="right"
                                       title="Information"
                                       data-content="{{__("textes.expertise_symptomes_quatrieme_info")}}">
                                    </i>
                                </div>

                                <select class="select2 form-control @error('symptome4') is-invalid @enderror"
                                        name="symptome4" id="symptome4">
                                </select>
                                <input type="hidden" class="form-control ponderation-number" id="ponderation4"
                                       name="ponderation4" value="{{ $ponderations[3] }}">
                                @error('symptome4')
                                <div class="invalid-feedback">
                                    {{ $errors->first('symptome4') }}
                                </div>
                                @enderror
                            </div>

                            <div class="col d-none d-xxl-block">
                                {{__("textes.expertise_symptomes_quatrieme_info")}}
                            </div>
                        </div>

                        <!-- Ligne de séparation entre les 2 champs -->
                        <hr class="input-separation hide-show">


                        <!----------------------
                        - SYMPTOME 5
                        ----------------------->
                        <div class="row hide-show">
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="symptome5"
                                           class="form-label"><strong>{{__("textes.expertise_symptomes_cinquieme")}}</strong>
                                    </label>
                                    <i class="btn fas fa-info-circle d-block d-xxl-none"
                                       data-toggle="popover"
                                       data-bs-placement="right"
                                       title="Information"
                                       data-content="{{__("textes.expertise_symptomes_cinquieme_info")}}">
                                    </i>
                                </div>

                                <select class="select2 form-control @error('symptome5') is-invalid @enderror"
                                        name="symptome5" id="symptome5">
                                </select>
                                <input type="hidden" class="form-control ponderation-number" id="ponderation5"
                                       name="ponderation5" value="{{ $ponderations[4] }}">
                                @error('symptome5')
                                <div class="invalid-feedback">
                                    {{ $errors->first('symptome5') }}
                                </div>
                                @enderror
                            </div>

                            <div class="col d-none d-xxl-block">
                                {{__("textes.expertise_symptomes_cinquieme_info")}}
                            </div>
                        </div>
                        @endlevel

                        <!----------------------
                        - MESSAGE D'INFORMATION POUR UN SEUL
                        ----------------------->
                        <p class="hide-show-reverse d-none mt-3">
                            {{ __("textes.expertise_symptomes_premier_info_complement") }}
                        </p>

                        <!----------------------
                        - BOUTONS
                        ----------------------->
                        <div class="row flex-column-reverse flex-sm-row mt-5">
                            <div class="col-sm-6 text-center">
                                <a href="{{ route('expertise.index') }}"
                                   class="btn btn-small-plus btn-symptome text-white">
                                    <i class="fas fa-times me-3"></i>{{ __("textes.expertise_symptomes_btn_annuler") }}
                                </a>
                            </div>

                            <div class="col-sm-6 text-center">
                                <button type="submit" class="btn btn-small-plus btn-symptome text-white">
                                    <i class="fas fa-check me-3"></i>{{ __("textes.expertise_symptomes_btn_valider") }}
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>


        <!-- Script pour que les popovers fonctionnent -->
        <script>
            @if (Session::has('mode'))
            hide_or_show();
            @endif

            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="popover"]'));
            const popoverList = popoverTriggerList.map(function (popoverTrigger) {
                return new bootstrap.Popover(popoverTrigger);
            });
        </script>
        <!-- Ajout Select2 aux balises <select> -->
        <script>
            // Récupération des symptomes + synonymes
            let symptomes = {!! json_encode($symptomes->toArray()) !!};
            prefix="{{ __("textes.expertise_concordant") }}: ";
            symptomes=symptomes.map(({traduction,id,concordant,is_concordant})=>({"id": id, "text": is_concordant?prefix+traduction.text:traduction.text,"concordant":concordant}));

            // Trie du tableau par ordre alphabétique
            symptomes.sort((a, b) => a.text.localeCompare(b.text));

            // Paramétrage de Select2
            $(document).ready(function () {
                let tab=[
                    {
                    "id": 0,
                    "text": "{{__("textes.expertise_symptomes_placeholder")}}",
                    "disabled": true,
                    "selected": true
                    },
                    ...symptomes
                ];
                $('.select2').select2({
                    theme: "bootstrap-5",
                    selectOnClose: true,
                    data: tab,
                });
                $('.select2').on('select2:select', function (e) {
                    let data = e.params.data;
                    if(data.concordant) {
                        $(e.currentTarget).val(data.concordant.id); // Select the option with a value of '1'
                        $(e.currentTarget).trigger('change');
                    }
                });
            });
        </script>
    </section>
@endsection
