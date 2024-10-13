@extends('layouts.template')
@section('title'){{__("textes.compte_change_mdp_page_nom")}}@endsection
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
    <section class="section">
        <div class="container h-100 d-flex justify-content-center align-items-center">
            <div class="row justify-content-center w-100">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-center">{{ __("textes.compte_mdp_reset_titre") }}</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __("textes.compte_email_label") }}</label>
                                    <div class="col-md-8">
                                        <input id="email" type="email"
                                               class="form-control @error('email') is-invalid @enderror" name="email"
                                               value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-0 mt-4">
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-small-text">{{ __("textes.compte_mdp_reset_bnt") }}</button>
                                    </div>
                                </div>
                                <input id="recaptchaResponse" name="recaptcha_response" type="hidden"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
