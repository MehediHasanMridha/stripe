@extends('layouts.template')
@section('title'){{__("textes.compte_register_page_nom")}}@endsection
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
@section('content')
    <section class="section h-100">
        <div class="container h-100 d-flex justify-content-center align-items-center">
            <div class="row justify-content-center w-100">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-center">{{ __("textes.compte_register_titre") }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="form-group row align-items-center my-2">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">
                                        <i class="fas fa-user"></i>
                                        {{ __("textes.compte_name_label") }}
                                    </label>

                                    <div class="col-md-8">
                                        <input id="name" type="name"
                                               class="form-control @error('name') is-invalid @enderror" name="name"
                                               value="{{ old('name') }}" required autocomplete="name">

                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row align-items-center my-2">
                                    <label for="firstname" class="col-md-4 col-form-label text-md-right">
                                        <i class="fas fa-user"></i>
                                        {{ __("textes.compte_firstname_label") }}
                                    </label>

                                    <div class="col-md-8">
                                        <input id="firstname" type="firstname"
                                               class="form-control @error('firstname') is-invalid @enderror" name="firstname"
                                               value="{{ old('firstname') }}" required autocomplete="firstname">

                                        @error('firstname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row align-items-center my-2">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">
                                        <i class="fas fa-envelope"></i>
                                        {{ __("textes.compte_email_label") }}
                                    </label>

                                    <div class="col-md-8">
                                        <input id="email" type="email"
                                               class="form-control @error('email') is-invalid @enderror" name="email"
                                               value="{{ old('email') }}" required autocomplete="email">

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row align-items-center my-2">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">
                                        <i class="fas fa-key"></i>
                                        {{ __("textes.compte_mdp_label") }}
                                    </label>

                                    <div class="col-md-8">
                                        <input id="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password" required autocomplete="new-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row align-items-center my-2">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                        <i class="fas fa-key"></i>
                                        {{ __("textes.compte_mdp_confirm_label") }}
                                    </label>

                                    <div class="col-md-8">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <!-- Input pour le score Recaptcha V3 -->
                                <input id="recaptchaResponse" name="recaptcha_response" type="hidden"/>

                                <div class="form-group row mt-3">
                                    <div class="justify-content-center d-flex">
                                        <button type="submit" class="btn btn-small-text btn-green">
                                            {{ __("textes.compte_register_bnt") }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
