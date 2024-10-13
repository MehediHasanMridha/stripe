@extends('layouts.template')
@section('title'){{__("textes.expertise_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a></li>
                    <li class="breadcrumb-item"><a class="arianne-link" href="/compte">{{ __("textes.arianne_compte") }}</a></li>
                    <li class="breadcrumb-item"><a class="arianne-link" href="/compte/conseillers">{{ __("textes.arianne_fiche_conseiller") }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_conseiller_creer") }}</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2 class="text-center my-3">{{__("textes.compte_conseillers_create_title")}}</h2>
                </div>
            </div>
            <div class="row mt-5">

                <form action="{{ route('compte.conseillers.store') }}" method="post">
                    @csrf

                    <div class="col-xxl-5 col text-size phone-margin">

                        <!----------  NOM ------------>
                        <div class="d-flex align-items-center">
                            <label class="form-label label-name create-size"><i
                                    class="fas fa-user-tie me-4"></i>{{ __("textes.conseillers_create_nom") }}</label>
                        </div>

                        <div class="mb-3">
                            <label for="nom" class="form-label">{{ __("textes.conseillers_create_nom") }}</label>
                            <input type="text" name="nom" id="nom" class="form-control">

                            <label for="prenom" class="form-label">{{ __("textes.conseillers_create_prenom") }}</label>
                            <input type="text" name="prenom" id="prenom" class="form-control">
                        </div>

                        <!---------- ADRESSE ---------->
                        <div class="d-flex align-items-center">
                            <label class="form-label label-name create-size"><i
                                    class="fas fa-map-marker-alt me-4"></i>{{__("textes.conseillers_show_adresse")}}
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="adresse" class="form-label">{{__("textes.conseillers_create_adresse")}}</label>
                            <input type="text" name="adresse" id="adresse" class="form-control">

                            <label for="cp" class="form-label">{{__("textes.conseillers_create_postal")}}</label>
                            <input type="number" name="cp" id="cp" class="form-control">

                            <label for="vile" class="form-label">{{__("textes.conseillers_create_ville")}}</label>
                            <input type="text" name="ville" id="ville" class="form-control">

                            <label for="departement"
                                   class="form-label">{{ __("textes.conseillers_create_departement") }}</label>
                            <input type="text" name="departement" id="departement" class="form-control">

                            <label for="region" class="form-label">{{ __("textes.conseillers_create_region") }}</label>
                            <input type="text" name="region" id="region" class="form-control">
                        </div>

                        <!---------- ADRESSE 2 ---------->
                        <div class="d-flex align-items-center">
                            <label class="form-label label-name create-size"><i
                                    class="fas fa-map-marker-alt me-4"></i>{{__("textes.conseillers_show_adresse")}}
                                2</label>
                        </div>

                        <div class="mb-3">
                            <label for="adresse2" class="form-label">{{__("textes.conseillers_create_adresse")}}</label>
                            <input type="text" name="adresse2" id="adresse2" class="form-control">

                            <label for="cp2" class="form-label">{{__("textes.conseillers_create_postal")}}</label>
                            <input type="number" name="cp2" id="cp2" class="form-control">

                            <label for="ville2" class="form-label">{{__("textes.conseillers_create_ville")}}</label>
                            <input type="text" name="ville2" id="ville2" class="form-control">

                            <label for="departement2"
                                   class="form-label">{{ __("textes.conseillers_create_departement") }}</label>
                            <input type="text" name="departement2" id="departement2" class="form-control">

                            <label for="region2" class="form-label">{{ __("textes.conseillers_create_region") }}</label>
                            <input type="text" name="region2" id="region2" class="form-control">
                        </div>

                        <!---------- CONTACT ---------->
                        <div class="d-flex align-items-center mt-5">
                            <label class="form-label label-name create-size"><i
                                    class="fas fa-phone-alt me-4"></i>{{__("textes.conseillers_show_contact")}}</label>
                        </div>

                        <div class="my-3">
                            <label for="phone" class="form-label">{{__("textes.conseillers_create_telephone")}}</label>
                            <input type="tel" name="tel" id="tel" class="form-control">

                            <label for="email" class="form-label">{{__("textes.conseillers_create_mail")}}</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>

                        <!---------- HORAIRES ---------->
                        <div class="d-flex align-items-center mt-5">
                            <label class="form-label label-name create-size"><i
                                    class="fas fa-calendar-alt me-4"></i>{{__("textes.conseillers_show_horaires")}}
                            </label>
                        </div>

                        <!-- Bouton "horaires sur rendez-vous" -->
                        <div class="mb-3 ms-5 me-5">
                            <div class="form-check form-switch">
                                <input class="form-check-input check-style" name="rdv" type="checkbox" id="rdv"
                                       onchange="return isChecked()">
                                <label class="form-check-label check-size"
                                       for="rdv">{{__("textes.conseillers_create_rendez_vous")}}</label>
                            </div>
                        </div>

                        <!-- Ajout des horaires d'un conseiller -->
                        <div class="mb-3" id="horaires-show">
                            <label for="horaires"
                                   class="form-label label-name create-size">{{__("textes.conseillers_create_horaires")}}</label>
                            <textarea type="text" class="form-control create-size" id="horaires" rows="3"
                                      aria-describedby="horaires"
                                      name="horaires">{{__("textes.conseillers_create_horaires_placeholder")}}</textarea>
                            <script type="text/javascript">
                                newEditor('#horaires');
                            </script>
                        </div>
                    </div>

                    <button class="btn btn-small-plus-conseiller mt-3" type="submit"><span
                            class="text-white save-btn">{{ __("textes.conseillers_create_btn") }}</span></button>
                </form>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        function isChecked() {
            if ($('#rdv').prop('checked')) {
                document.getElementById('horaires-show').style.display = 'none';
            } else {
                document.getElementById('horaires-show').style.display = 'contents';
            }

            return false;
        }
    </script>
@endsection