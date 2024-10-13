@extends('layouts.template')
@section('title'){{__("textes.expertise_page_nom")}}@endsection
@push('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $("#newModal").modal('show');
        });
    </script>
@endpush
@section('content')
    <section class="section">
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_expertise") }}</li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <div class="d-flex align-items-center justify-content-center">
                <!-- Affiche le titre de la page -->
                <h2 class="text-center my-3">{{ __("textes.expertise_index_titre") }}</h2>
            </div>

            <div class="container phone-margin">
                <i>{{__("textes.expertise_gp_text")}}</i>
            </div>

            <!-- Boutons qui amène l'utilisateur vers à la page contenant la liste de tous les ingrédients, avec comme argument la langue sélectionnée -->
            <div class="d-flex flex-column align-items-center mt-4">
                <a class="btn btn-large mb-4" href="{{ route('expertise.symptome') }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <i class="fas fa-thermometer-half icon-white ms-4 pe-3"></i>
                        <span class="btn-text pe-5">{{ __("textes.expertise_index_btn_text") }}</span>
                        <div></div>
                    </div>
                </a>

                <!--Boutons qui amène l'utilisateur vers à la page contenant la liste de tous les symptomes, avec comme argument la langue sélectionnée -->
                <a class="btn btn-large mb-4" href="{{ route('syndromes.liste') }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <i class="fas fa-stethoscope icon-white ms-4 pe-3"></i>
                        <span class="btn-text pe-5">{{ __("textes.expertise_index_btn_syndrome") }}</span>
                        <div></div>
                    </div>
                </a>
            </div>
        </div>

        <!-- MODAL -->
        <div class="modal fade" id="newModal" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content modal-bg">
                    <!--Titre de la modal et bouton pour la fermer-->
                    <div class="modal-header">
                        <h4 class="modal-title text-white title-modal-size"><i
                                class="fas fa-exclamation-triangle"></i> {{__("textes.expertise_index_modal_titre")}}
                        </h4>
                        <button type="button" class="btn-close btn-close-white title-modal-size"
                                data-bs-dismiss="modal"></button>
                    </div>
                    <!-- text d'avertisement -->
                    <div class="modal-body text-white">
                        <div class="paragraph-modal-size">
                            <ul>
                                <li><strong> {{__("textes.expertise_but_informatif")}} </strong></li>
                                <li><strong> {{__("textes.expertise_index_modal_juridique")}} </strong></li>
                                <ul>
                                    <li>{{__("textes.expertise_index_modal_personnes")}}
                                        <strong> {{__("textes.expertise_index_modal_consultants")}} </strong> {{__("textes.expertise_index_modal_personnes2")}}
                                    </li>
                                    <li>{{__("textes.expertise_index_modal_pharmacie")}} </li>
                                    <li>{{__("textes.expertise_index_modal_prescription")}}</li>
                                    <li>{{__("textes.expertise_index_modal_medicaments")}} </li>
                                </ul>
                                <li><strong>{{__("textes.expertise_index_modal_pharmacopée")}}</strong></li>
                                <ul>
                                    <li>{{__("textes.expertise_index_modal_proposition")}}</li>
                                </ul>

                                <br>

                                <strong><p
                                        class="border border-white py-2 px-2"> {{__("textes.expertise_index_modal_remboursement")}}
                                        <br> {{__("textes.expertise_index_modal_phyto")}} </p></strong>

                                <strong>
                                    <div class="border border-white py-2 px-2">
                                        <p> {{__("textes.expertise_index_modal_energetique")}} </p>
                                        <ul>
                                            <li>{{__("textes.expertise_index_modal_visite")}}</li>
                                            <li>{{__("textes.expertise_index_modal_prescrit")}}</li>
                                        </ul>
                                    </div>
                                </strong>

                            </ul>
                        </div>
                    </div>
                    <!-- Bouton OK qui ramene a la page voulu -->
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn modal-btn px-4" data-bs-dismiss="modal"><span
                                class="modal-text"> {{__("textes.expertise_index_modal_btn_ok")}} </span></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
