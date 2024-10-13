@extends('layouts.template')
@section('title') {{__("textes.expertise_page_nom")}} @endsection
@section('content')
    <section class="section">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="container">
            <!--Lien d'arianne ou Breadcrump-->
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="arianne-link" href="/">{{ __("textes.arianne_accueil") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("textes.arianne_compte") }}</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2 class="text-center my-3">{{ __("textes.compte_index_title") }}</h2>

                    @if($sub)
                        <div class="alert alert-success" role="alert">
                            {{ __("textes.compte_info_abonnement_en_cours") }}
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            {{ __("textes.compte_info_abonnement_terminee") }} <a
                                href="{{route("stripe.index")}}">{{ __("textes.compte_info_abonnement_lien_abonnement") }}</a>
                        </div>
                    @endif


                    <div class="d-flex flex-column align-items-center mt-4">
                        <a data-bs-toggle="modal" href="#modal_email" role="button" class="btn btn-large">
                            <span class="btn-text">{{ __("textes.compte_btn_changer_email") }}</span>
                        </a>
                        <a data-bs-toggle="modal" href="#modal_password" role="button" class="btn btn-large">
                            <span class="btn-text">{{ __("textes.compte_btn_changer_mot_de_passe") }}</span>
                        </a>
                        <a data-bs-toggle="modal" href="#modal_suppr" role="button" class="btn btn-large">
                            <span class="btn-text">{{ __("textes.compte_btn_fiche_supprimer") }}</span>
                        </a>
                        @role('vip')
                        @else
                            <a href="{{route("stripe.index")}}" class="btn btn-large">
                                <span class="btn-text">{{ __("textes.compte_btn_changer_abonnement") }}</span>
                            </a>
                        @endrole
                        @role('praticien')
                        <a href="{{route("compte.conseillers.index")}}" class="btn btn-large">
                            <span class="btn-text">{{ __("textes.compte_btn_fiche_conseiller") }}</span>
                        </a>
                        @endrole
                        <a href="{{route("auth.logout")}}" class="btn btn-large-danger">
                            <span class="btn-text">{{ __("textes.compte_btn_changer_deconnexion") }}</span>
                        </a>
                    </div>
                    @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show justify-content-between d-flex "
                             role="alert">
                            <div>
                                <strong>Success : </strong> {{session()->get('success')}}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session()->has('failed'))
                        <div class="alert alert-danger alert-dismissible fade show justify-content-between d-flex "
                             role="alert">
                            <div>
                                <strong>Failed : </strong> {{session()->get('failed')}}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal_suppr" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
             tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __("textes.compte_btn_fiche_supprimer1")}}</h5>
                        <button type="button" class="btn-close " data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="bold" id="userEmail"></p>
                        <a data-bs-toggle="modal" href="#modal_suppr2" role="button" class="btn btn-large">
                            <span class="btn-text">{{ __("textes.compte_btn_fiche_supprimer2")}}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal_suppr2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
       <div class="modal-dialog modal-dialog-centered">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">{{ __("textes.compte_btn_fiche_supprimer3")}}</h5>
                   <button type="button" class="btn-close " data-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-footer">
                   <form method="POST" action="{{route("compte.delete")}}">
                       @csrf
                       <input type="hidden" id="userId" name="userId"/>
                       <button type="button" class="btn btn-secondary"  data-dismiss="modal">{{ __("textes.compte_btn_fiche_supprimer4")}}</button>
                       <button type="submit" href="#modal_suppr3" class="btn-primary-close rounded">{{ __("textes.compte_btn_fiche_supprimer5")}}</button>
                   </form>
               </div>
           </div>
       </div>
   </div>
    @if (session()->has('deleted'))
        <script>
            fetch("{{route("compte.deleted")}}",{method:"POST", headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }})
            $(document).ready(function() {
                $('#modal_suppr3').modal('show');
            });
            setTimeout(function() {
                window.location.href = "{{route("compte.index")}}"
            }, 10000); // 2 second
        </script>
    @endif
<div class="modal fade" id="modal_suppr3" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">{{ __("textes.compte_btn_fiche_supprimer6")}}</h5>
          </div>
      </div>
  </div>
</div>

        <div class="modal fade" id="modal_email" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
             tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __("textes.compte_changer_email_titre") }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route("compte.email")}}" method="post">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label w-100">
                                    <i class="bi bi-envelope-fill"></i>
                                    {{ __("textes.compte_changer_email_titre") }}
                                </label>
                                <input type="email" name="email" id="email" class="form-control"
                                       placeholder="{{ __("textes.compte_changer_email_placeholder") }}"
                                       value="{{\Illuminate\Support\Facades\Auth::user()["email"]}}" required>
                            </div>
                            <input type="submit" class="btn btn-large text-white"
                                value="{{ __("textes.compte_btn_modifier") }}"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal_password" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
             tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __("textes.compte_changer_mdp_titre") }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route("compte.password")}}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="old_password" class="form-label">
                                    <i class="bi bi-key-fill"></i>
                                    {{ __("textes.compte_changer_mdp_ancien") }}
                                </label>
                                <input type="password" name="old_password" id="old_password" class="form-control w-100"
                                       placeholder="{{ __("textes.compte_changer_mdp_ancien_placeholder") }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-key-fill"></i>
                                    {{ __("textes.compte_changer_mdp_nouveau") }}
                                </label>
                                <input type="password" minlength="8" name="password" id="password"
                                       class="form-control w-100"
                                       placeholder="{{ __("textes.compte_changer_mdp_nouveau_placeholder") }}"
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-key-fill"></i>
                                    {{ __("textes.compte_changer_mdp_confirm") }}
                                </label>
                                <input type="password" minlength="8" name="password_confirm" id="password_confirm"
                                       class="form-control w-100"
                                       placeholder="{{ __("textes.compte_changer_mdp_confirm_placeholder") }}"
                                       required>
                            </div>

                            <input type="submit" class="btn btn-large text-white" value="{{ __("textes.compte_btn_modifier") }}"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
