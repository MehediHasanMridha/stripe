@extends('layouts.admin-template')
@section('title'){{__("textes.conseillersAdmin_page_nom")}}@endsection
@section('content')
    <section>
        <div class="container-fluid container-xl">
            <div class="mb-3 mt-4">
                <!-- div qui permet d'afficher les éléments sur la même ligne mais espacés -->
                <div class="d-flex align-items-center justify-content-between">
                    <div></div>
                    <!-- Affiche dans un titre de niveau 3 le nom du conseiller et son id -->
                    <h3> {{__("textes.conseillersAdmin_create_practicien")}} </h3>
                    <!-- Affiche une croix permettant de revenir a la page index -->
                    <a href="{{ route('conseillersAdmin.index') }}">
                        <button type="button" class="btn-close ms-2 text-border-space-right icon-size"
                                aria-label="Close"></button>
                    </a>
                </div>
            </div>

            <div class="row mt-5">

                <form action="{{ route('conseillersAdmin.store') }}" method="post">
                    @csrf

                    <div class="col-xxl-5 col text-size phone-margin">

                        <!----------  NOM ------------>
                        <div class="d-flex align-items-center">
                            <label class="form-label label-name create-size"><i
                                    class="fas fa-user-tie me-4"></i>{{ __("textes.conseillersAdmin_create_nom") }}
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="nom" class="form-label">{{ __("textes.conseillersAdmin_create_nom") }}</label>
                            <input type="text" name="nom" id="nom" class="form-control">

                            <label for="prenom"
                                   class="form-label">{{ __("textes.conseillersAdmin_create_prenom") }}</label>
                            <input type="text" name="prenom" id="prenom" class="form-control">
                        </div>

                        <!---------- ADRESSE ---------->
                        <div class="d-flex align-items-center">
                            <label class="form-label label-name create-size"><i
                                    class="fas fa-map-marker-alt me-4"></i>{{__("textes.conseillers_show_adresse")}}
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="adresse"
                                   class="form-label">{{__("textes.conseillersAdmin_create_adresse")}}</label>
                            <input type="text" name="adresse" id="adresse" class="form-control">

                            <label for="cp" class="form-label">{{__("textes.conseillersAdmin_create_postal")}}</label>
                            <input type="number" name="cp" id="cp" class="form-control">

                            <label for="vile" class="form-label">{{__("textes.conseillersAdmin_create_ville")}}</label>
                            <input type="text" name="ville" id="ville" class="form-control">

                            <label for="departement"
                                   class="form-label">{{ __("textes.conseillersAdmin_create_departement") }}</label>
                            <input type="text" name="departement" id="departement" class="form-control">

                            <label for="region"
                                   class="form-label">{{ __("textes.conseillersAdmin_create_region") }}</label>
                            <input type="text" name="region" id="region" class="form-control">
                        </div>

                        <!---------- ADRESSE 2 ---------->
                        <div class="d-flex align-items-center">
                            <label class="form-label label-name create-size"><i
                                    class="fas fa-map-marker-alt me-4"></i>{{__("textes.conseillers_show_adresse2")}}
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="adresse2"
                                   class="form-label">{{__("textes.conseillersAdmin_create_adresse")}}</label>
                            <input type="text" name="adresse2" id="adresse2" class="form-control">

                            <label for="cp2" class="form-label">{{__("textes.conseillersAdmin_create_postal")}}</label>
                            <input type="number" name="cp2" id="cp2" class="form-control">

                            <label for="ville2"
                                   class="form-label">{{__("textes.conseillersAdmin_create_ville")}}</label>
                            <input type="text" name="ville2" id="ville2" class="form-control">

                            <label for="departement2"
                                   class="form-label">{{ __("textes.conseillersAdmin_create_departement") }}</label>
                            <input type="text" name="departement2" id="departement2" class="form-control">

                            <label for="region2"
                                   class="form-label">{{ __("textes.conseillersAdmin_create_region") }}</label>
                            <input type="text" name="region2" id="region2" class="form-control">
                        </div>

                        <!---------- CONTACT ---------->
                        <div class="d-flex align-items-center mt-5">
                            <label class="form-label label-name create-size"><i
                                    class="fas fa-phone-alt me-4"></i>{{__("textes.conseillers_show_contact")}}</label>
                        </div>

                        <div class="my-3">
                            <label for="phone"
                                   class="form-label">{{__("textes.conseillersAdmin_create_telephone")}}</label>
                            <input type="tel" name="tel" id="tel" class="form-control">

                            <label for="email" class="form-label">{{__("textes.conseillersAdmin_create_mail")}}</label>
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
                                       for="rdv">{{__("textes.conseillersAdmin_create_rendez_vous")}}</label>
                            </div>
                        </div>

                        <!-- Ajout des horaires d'un conseiller -->
                        <div class="mb-3" id="horaires-show">
                            <label for="horaires"
                                   class="form-label label-name create-size">{{__("textes.conseillersAdmin_create_horaires")}}</label>
                            <textarea type="text" class="form-control create-size" id="horaires" rows="3"
                                      aria-describedby="horaires"
                                      name="horaires">{{__("textes.conseillersAdmin_create_horaires_suggestion")}}</textarea>
                            <script type="text/javascript">
                                newEditor('#horaires');
                            </script>
                        </div>
                    </div>

                    <button class="btn btn-small-plus-conseiller mt-3" type="submit"><span
                            class="text-white save-btn"> {{ __("textes.conseillersAdmin_create_conseillers") }} </span>
                    </button>
                </form>
            </div>
        </div>

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
    </section>
@endsection
