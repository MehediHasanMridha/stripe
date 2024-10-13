@extends('layouts.admin-template')
@section('title'){{__("textes.traductions_page_nom")}}@endsection
@section('content')
    <section>
        <div class="container-fluid container-xl">

            <!-- Titre de la page -->
            <div class="text-center">
                <h2 class="text-titre">{{__("textes.traductions_create_titre")}}</h2>
            </div>

            <hr>

            <!-- Formulaire poru créer une nouvelle langues -->
            <form action="{{ route('traductions.store') }}" method="POST">
                @csrf
                <table class="table table-striped table-hover table-bordered">

                    <!-- Nom des colones du tableau -->
                    <thead>
                    <tr>
                        <th scope="col" class="w-25 th-legend">
                            <strong>{{__("textes.traductions_create_soustitre_cle")}}</strong></th>
                        <th scope="col" class="th-legend">
                            <strong>{{__("textes.traductions_create_soustitre_texte")}}</strong></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th class="text-center fw-bold th-page" colspan="2" scope="row">
                            <h4>{{__("textes.traductions_create_soustitre_langue")}}</h4></th>
                    </tr>
                    <tr>
                        <th scope="row"><label for="selectLangue">{{__("textes.traductions_create_soustitre_langue")}}
                                <span style="color: red; vertical-align: top;">*</span></label></th>
                        <td>
                            <select type="text" id="selectLangue" name="selectLangue"
                                    class="form-control @error('selectLangue') is-invalid @enderror">
                                <option value="" hidden>{{__("textes.traductions_create_select_langue")}}</option>
                                @foreach($iso_639_1 as $iso => $nom)
                                    <option value="{{ $iso }}">{{ $nom }}</option>
                                @endforeach
                            </select>

                            @error('selectLangue')
                            <div class="invalid-feedback">
                                {{ $errors->first('selectLangue') }}
                            </div>
                            @enderror
                        </td>
                    </tr>
                    @foreach($model as $pages => $textes)
                        <tr>
                            <th class="text-center fw-bold th-page" colspan="2" scope="row"><h4>{{ $pages }}</h4></th>
                        </tr>
                        @foreach($textes as $key => $texte)
                            <tr>
                                <th scope="row"><label for="input{{ $key }}">{{ $key }}</label></th>
                                <td><textarea id="input{{ $key }}" name="{{ $key }}"
                                              placeholder="Exemple : {{ $texte }}" class="form-control"></textarea></td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>

                <button type="submit"
                        class="btn btn-color text-white me-3 p-2">{{__("textes.traductions_create_btn_add_langue")}}</button>
                <a href="{{ route('traductions.index') }}"
                   class="btn btn-color text-white ms-3 p-2">{{__("textes.traductions_create_btn_annuler")}}</a>
            </form>
        </div>
    </section>
@endsection
