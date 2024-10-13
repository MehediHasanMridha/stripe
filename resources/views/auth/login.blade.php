@extends('layouts.template')
@push('script')
    <script src="https://www.google.com/recaptcha/api.js?render=6Lf9DfUdAAAAANjgvrSewVtzQOrsE4IwpO-2PBHs"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('6Lf9DfUdAAAAANjgvrSewVtzQOrsE4IwpO-2PBHs', {action: 'contact'}).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                if (recaptchaResponse) recaptchaResponse.value = token;
            });
        });
    </script>
@endpush
@section('title'){{__("textes.compte_login_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_login") }}</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="mb-5">
                        <div class="d-flex align-items-center justify-content-center">
                            <!-- Affiche le titre de la page -->
                            <h2 class="text-center my-3">{{ __("textes.compte_login_titre") }}</h2>
                        </div>

                        <div class="row row-show mb-5 mt-4">
                            <div class="col col-marge border-separator d-flex justify-content-center">
                                <form method="POST" action="{{ route('login') }}" class="w-75">
                                    @csrf
                                    <h3 class="text-center text-show-size">{{ __("textes.compte_login_question") }}</h3>
                                    <div class="form-group row my-2">
                                        <div class="mx-auto">
                                            <input id="email" placeholder="{{ __("textes.compte_login_email_placeholder") }}" type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   name="email" value="{{ old('email') }}" required autocomplete="email"
                                                   autofocus>

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row my-2">
                                        <div class="mx-auto">
                                            <input id="password" placeholder="{{ __("textes.compte_login_mdp_placeholder") }}" type="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   name="password" required autocomplete="current-password">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                       id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">
                                                    {{ __("textes.compte_login_se_souvenir") }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group d-flex justify-content-center">

                                        <button type="submit" class="btn btn-small-text mt-4">
                                            {{ __("textes.compte_login_btn") }}
                                        </button>

                                    </div>
                                    <div class="form-group d-flex justify-content-center">
                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __("textes.compte_login_mdp_oublie") }}
                                            </a>
                                        @endif
                                    </div>
                                    <input id="recaptchaResponse" name="recaptcha_response" type="hidden"/>
                                </form>
                            </div>
                            <div class="col col-marge">
                                <h3 class="text-center text-show-size">{{ __("textes.compte_login_compte_question") }}</h3>
                                <div class="w-75 mx-auto">
                                    <p>{{ __("textes.compte_login_compte_paragraphe") }}</p>
                                </div>
                                <div class="d-flex justify-content-center ">
                                    <a href="{{ route('register') }}">
                                        <button class="btn btn-small-text mt-4">
                                            {{ __("textes.compte_login_compte_btn") }}
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
