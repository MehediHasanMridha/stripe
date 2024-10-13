@extends('layouts.template')
@section('title'){{__("textes.expertise_page_nom")}}@endsection
@section('content')
    <section class="section">
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item"><a class="arianne-link" href="/compte">Compte</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ma fiche conseiller</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2 class="text-center my-3">Ma fiche conseiller</h2>
                </div>
            </div>
            <div class="row mt-5">

                <form action="{{ route('compte.conseillers.update') }}" method="post">
                    @csrf

                    <div class="col-xxl-5 col text-size phone-margin w-50">

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
                                    class="fas fa-map-marker-alt me-4"></i>{{__("textes.conseillers_show_adresse")}}
                                2</label>
                        </div>

                        <div class="mb-3">
                            <label for="adresse2" class="form-label">{{__("textes.conseillersAdmin_create_adresse")}}
                                2</label>
                            <input type="text" name="adresse2" id="adresse2" class="form-control">

                            <label for="cp2" class="form-label">{{__("textes.conseillersAdmin_create_postal")}}
                                2</label>
                            <input type="number" name="cp2" id="cp2" class="form-control">

                            <label for="ville2" class="form-label">{{__("textes.conseillersAdmin_create_ville")}}
                                2</label>
                            <input type="text" name="ville2" id="ville2" class="form-control">

                            <label for="departement2"
                                   class="form-label">{{ __("textes.conseillersAdmin_create_departement") }} 2</label>
                            <input type="text" name="departement2" id="departement2" class="form-control">

                            <label for="region2" class="form-label">{{ __("textes.conseillersAdmin_create_region") }}
                                2</label>
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

                        <div class="mb-3 ms-5 me-5">
                            <div class="form-check form-switch">
                                <input class="form-check-input check-style" name="rdv" type="checkbox" id="rdv"
                                       onchange="return isChecked()">
                                <label class="form-check-label check-size"
                                       for="rdv">{{__("textes.conseillersAdmin_create_rendez_vous")}}</label>
                            </div>
                        </div>

                        <table class="text-size" id="horaires">
                            <tr class="mb-3">
                                <td><label for="lundi"
                                           class="form-label">{{ __("textes.conseillers_show_horraire_lundi") }}</label>
                                </td>
                                <td class="d-flex align-items-center">
                                    <input type="time" name="lundi1" id="lundi" class="form-control">
                                    <input type="time" name="lundi2" id="lundi" class="form-control">
                                    <span class="fs-4 fw-bold mx-2">/</span>
                                    <input type="time" name="lundi3" id="lundi" class="form-control">
                                    <input type="time" name="lundi4" id="lundi" class="form-control">
                                </td>
                            </tr>
                            <tr class="mb-3">
                                <td><label for="mardi"
                                           class="form-label">{{ __("textes.conseillers_show_horraire_mardi") }}</label>
                                </td>
                                <td class="d-flex align-items-center">
                                    <input type="time" name="mardi1" id="mardi" class="form-control">
                                    <input type="time" name="mardi2" id="mardi" class="form-control">
                                    <span class="fs-4 fw-bold mx-2">/</span>
                                    <input type="time" name="mardi3" id="mardi" class="form-control">
                                    <input type="time" name="mardi4" id="mardi" class="form-control">
                                </td>
                            </tr>
                            <tr class="mb-3">
                                <td><label for="mercredi"
                                           class="form-label">{{ __("textes.conseillers_show_horraire_mercredi") }}</label>
                                </td>
                                <td class="d-flex align-items-center">
                                    <input type="time" name="mercredi1" id="mercredi" class="form-control">
                                    <input type="time" name="mercredi2" id="mercredi" class="form-control">
                                    <span class="fs-4 fw-bold mx-2">/</span>
                                    <input type="time" name="mercredi3" id="mercredi" class="form-control">
                                    <input type="time" name="mercredi4" id="mercredi" class="form-control">
                                </td>
                            </tr>
                            <tr class="mb-3">
                                <td><label for="jeudi"
                                           class="form-label">{{ __("textes.conseillers_show_horraire_jeudi") }}</label>
                                </td>
                                <td class="d-flex align-items-center">
                                    <input type="time" name="jeudi1" id="jeudi" class="form-control">
                                    <input type="time" name="jeudi2" id="jeudi" class="form-control">
                                    <span class="fs-4 fw-bold mx-2">/</span>
                                    <input type="time" name="jeudi3" id="jeudi" class="form-control">
                                    <input type="time" name="jeudi4" id="jeudi" class="form-control">
                                </td>
                            </tr>
                            <tr class="mb-3">
                                <td><label for="vendredi"
                                           class="form-label">{{ __("textes.conseillers_show_horraire_vendredi") }}</label>
                                </td>
                                <td class="d-flex align-items-center">
                                    <input type="time" name="vendredi1" id="vendredi" class="form-control">
                                    <input type="time" name="vendredi2" id="vendredi" class="form-control">
                                    <span class="fs-4 fw-bold mx-2">/</span>
                                    <input type="time" name="vendredi3" id="vendredi" class="form-control">
                                    <input type="time" name="vendredi4" id="vendredi" class="form-control">
                                </td>
                            </tr>
                            <tr class="mb-3">
                                <td><label for="samedi"
                                           class="form-label">{{ __("textes.conseillers_show_horraire_samedi") }}</label>
                                </td>
                                <td class="d-flex align-items-center">
                                    <input type="time" name="samedi1" id="samedi" class="form-control">
                                    <input type="time" name="samedi2" id="samedi" class="form-control">
                                    <span class="fs-4 fw-bold mx-2">/</span>
                                    <input type="time" name="samedi3" id="samedi" class="form-control">
                                    <input type="time" name="samedi4" id="samedi" class="form-control">
                                </td>
                            </tr>
                            <tr class="mb-3">
                                <td><label for="dimanche"
                                           class="form-label">{{ __("textes.conseillers_show_horraire_dimanche") }}</label>
                                </td>
                                <td class="d-flex align-items-center">
                                    <input type="time" name="dimanche1" id="dimanche" class="form-control">
                                    <input type="time" name="dimanche2" id="dimanche" class="form-control">
                                    <span class="fs-4 fw-bold mx-2">/</span>
                                    <input type="time" name="dimanche3" id="dimanche" class="form-control">
                                    <input type="time" name="dimanche4" id="dimanche" class="form-control">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <button class="btn btn-small-plus-conseiller mt-3" type="submit"><span
                            class="text-white save-btn"> {{ __("Enregistrer les modifications") }} </span></button>
                </form>
            </div>

        </div>
    </section>
@endsection
