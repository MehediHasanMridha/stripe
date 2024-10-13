@extends('layouts.template')
@section('title'){{__("textes.stripe_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_stripe") }}</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            <div class="text-center">
                <h2>{{__("textes.stripe_index_text")}}</h2>
            </div>

            <div class="container p-5">
                <div class="row">
                    <div class="col-lg-1 col-md-12 mb-4"></div>
                    <div class="col-lg-4 col-md-12 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="text-center p-3">
                                    <h5 class="card-title">{{__("textes.stripe_mensuel_title")}}</h5>
                                    <small>{{__("textes.stripe_mensuel_subtitle")}}</small>
                                    <br><br>
                                    <span
                                        class="h2">{{($month["unit_amount"]/100)}}{{__("textes.stripe_money_unit")}}</span>{{__("textes.stripe_mensuel_duree")}}
                                    <br><br>
                                </div>
                                <p class="card-text">{{__("textes.stripe_mensuel_description")}}</p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item border-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-check" viewBox="0 0 16 16">
                                        <path
                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                    </svg>{{__("textes.stripe_mensuel_avantage1")}}</li>
                                <li class="list-group-item border-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-check" viewBox="0 0 16 16">
                                        <path
                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                    </svg>{{__("textes.stripe_mensuel_avantage2")}}</li>
                                <li class="list-group-item ">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-check" viewBox="0 0 16 16">
                                        <path
                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                    </svg>{{__("textes.stripe_mensuel_avantage3")}}</li>
                            </ul>
                            <div class="card-body text-center">
                                <a href="{{route("stripe.checkout",[$month["id"]])}}"
                                   class="btn btn-outline-green btn-lg">{{__("textes.stripe_btn")}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 mb-4"></div>
                    <div class="col-lg-4 col-md-12 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="text-center p-3">
                                    <h5 class="card-title">{{__("textes.stripe_annuel_title")}}</h5>
                                    <small>{{__("textes.stripe_annuel_subtitle")}}</small>
                                    <br><br>
                                    <span
                                        class="h2">{{($year["unit_amount"]/100)}}{{__("textes.stripe_money_unit")}}</span>{{__("textes.stripe_annuel_duree")}}
                                    <br><br>
                                </div>
                                <p class="card-text">{{__("textes.stripe_annuel_description")}}</p>
                            </div>
                            <ul class="list-group list-group-flush ">
                                <li class="list-group-item border-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-check" viewBox="0 0 16 16">
                                        <path
                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                    </svg>{{__("textes.stripe_annuel_avantage1")}}</li>
                                <li class="list-group-item border-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-check" viewBox="0 0 16 16">
                                        <path
                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                    </svg>{{__("textes.stripe_annuel_avantage2")}}</li>
                                <li class="list-group-item border-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-check" viewBox="0 0 16 16">
                                        <path
                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                    </svg>{{__("textes.stripe_annuel_avantage3")}}</li>
                            </ul>
                            <div class="card-body text-center">
                                <a href="{{route("stripe.checkout",[$year["id"]])}}"
                                   class="btn btn-outline-green btn-lg">{{__("textes.stripe_btn")}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .border-none {
                border-width: 0px !important;
            }

            .btn-outline-green {
                color: white;
                background-color: #328E1AFF;
                border-color: #328E1AFF
            }

            .btn-outline-green:hover {
                color: white;
                background-color: #328E1AFF;
                border-color: #328E1AFF
            }

            .btn-outline-green:focus,
            .btn-outline-green.focus {
                box-shadow: 0 0 0 .2rem rgba(50, 142, 26, 0.5)
            }

            .btn-outline-green.disabled,
            .btn-outline-green:disabled {
                color: white;
                background-color: #328E1AFF;
                border-color: #328E1AFF
            }

            .btn-outline-green:not(:disabled):not(.disabled):active,
            .btn-outline-green:not(:disabled):not(.disabled).active,
            .show > .btn-outline-green.dropdown-toggle {
                color: white;
                background-color: #328E1AFF;
                border-color: #328E1AFF
            }

            .btn-outline-green:not(:disabled):not(.disabled):active:focus,
            .btn-outline-green:not(:disabled):not(.disabled).active:focus,
            .show > .btn-outline-green.dropdown-toggle:focus {
                box-shadow: 0 0 0 .2rem rgba(50, 142, 26, 0.53)
            }
        </style>
    </section>
@endsection
