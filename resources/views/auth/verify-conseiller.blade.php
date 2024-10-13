@extends('layouts.template')
@section('title'){{__("textes.login_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">
            <h5 class="text-center">{{ __("textes.compte_verif_compte_titre") }}</h5>
            <div class="text-center">
                <button class="btn btn-primary px-5" onclick="location.reload()">{{ __("textes.compte_btn_rafraichir") }}</button>
            </div>
        </div>
    </section>
@endsection
