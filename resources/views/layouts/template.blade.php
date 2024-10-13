<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


$langues = DB::select("SELECT langue from traductions");
$langues = [(object) array('langue' => 'fr')];
$parametres = Route::getCurrentRoute()->originalParameters();
?>
<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
    <head>
        <!-- On déclare l'encodage en UTF-8 -->
        <meta charset='utf-8'/>
        <!-- On réinitialise le zoom pour les mobiles -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <!-- Ressources ccs -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
        <link href="{{ asset('css/template.css') }}" type="text/css" rel="stylesheet">


        <!-- Script Fontawesome, jquery, bootstrap, popper -->
        <script src="https://kit.fontawesome.com/a650f20918.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
                integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
                integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
                crossorigin="anonymous"></script>

        <!-- Editeur enrichi -->
        <script src="/plugins/ckeditor5/build/ckeditor.js"></script>
        <script src="/js/editor.js"></script>

        <!-- Select2 : Recherche dans les <select> -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- Theme Boostrap 5 pour Select2  -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />

        @stack('script')

        <title> @yield('title')</title>
    </head>

    <body class="d-flex flex-column h-100">

        <header>
            <!-- Intégration de la navbar sur le modèle Bootstrap -->
            <nav class="navbar navbar-color navbar-text navbar-expand-lg py-0 fixed-top">
                <div class="navbar-full-screen">
                    <!-- Mise en place du logo JZ ainsi que du texte " Santé naturelle" a gauche de la navbar -->
                    <a class="navbar-brand brand-text" href="{{ route('accueil.index') }}">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo JZ" class="brand-logo">
                        <span class='brand-full'> {{__("textes.header_link_brand_text")}} </span>
                    </a>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <!-- Liste avec tous les éléments de de la partie droite de la navbar -->
                        <ul class="navbar-nav position-absolute end-0">
                            <!-- Eléments de la liste contenant l'icone et le texte de chaque onglet -->
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="javascript:history.go(-1)">
                                    <i class="fas fa-arrow-left text-white mt-1"></i>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="javascript:history.go(+1)">
                                    <i class="fas fa-arrow-right text-white mt-1 mx-3"></i>
                                </a>
                            </li>
                            <li class="nav-item burger">
                                <a class="m-3" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
                                   aria-controls="offcanvasExample">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="34px" height="27px"
                                         viewBox="0 0 34 27" enable-background="new 0 0 34 27" xml:space="preserve">
                                                    <rect fill="#FFFFFF" width="34" height="4"/>
                                        <rect y="11" fill="#FFFFFF" width="34" height="4"/>
                                        <rect y="23" fill="#FFFFFF" width="34" height="4"/>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Canvas Menu -->
            <div class="offcanvas offcanvas-end d-flex flex-column flex-shrink-0 p-3 navbar-color" style="width: 280px;"
                 tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                <div class="d-flex align-items-center mb-md-0 me-md-auto text-white">
                    <span class="fs-4">{{__("textes.header_menu")}} </span>
                </div>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <!-- Eléments de la liste contenant l'icone et le texte de chaque onglet -->
                    <li class="nav-item nav-space-full">
                        <a class="nav-link navbar-text" href="{{ route('accueil.index') }}">
                            <i class="fas fa-home"></i>
                            <span class='navbar-full'> {{__("textes.header_link_accueil")}}  </span>
                        </a>
                    </li>

                    <li class="nav-item nav-space-full">
                        <a class="nav-link navbar-text" href="{{ route('formules.index') }}">
                            <i class="fas fa-capsules"></i>
                            <span class='navbar-full'> {{__("textes.header_link_formules")}} </span>
                        </a>
                    </li>

                    <li class="nav-item nav-space-full">
                        <a class="nav-link navbar-text" href="{{ route('ingredients.index') }}">
                            <i class="fas fa-leaf"></i>
                            <span class='navbar-full'> {{__("textes.header_link_ingredients")}} </span>
                        </a>
                    </li>

                    <li class="nav-item nav-space-full">
                        <a class="nav-link navbar-text" href="{{ route('expertise.index') }}">
                            <i class="fas fa-stethoscope"></i>
                            <span class='navbar-full'> {{__("textes.header_link_expertise")}} </span>
                        </a>
                    </li>

                    <li class="nav-item nav-space-full">
                        <a class="nav-link navbar-text" href="{{ route('actualites.index') }}">
                            <i class="fas fa-newspaper"></i>
                            <span class='navbar-full'>{{__("textes.accueil_btn_actualite")}} </span>
                        </a>
                    </li>

                    <li class="nav-item nav-space-full">
                        <a class="nav-link navbar-text" href="{{ route('conseillers.index') }}">
                            <i class="fas fa-user-md"></i>
                            <span class='navbar-full'>  {{__("textes.header_link_conseillers")}} </span>
                        </a>
                    </li>
                    @level(5)
                    <li class="nav-item nav-space-full">
                        <a class="nav-link navbar-text" href="{{ route('accueil.admin') }}">
                            <i class="fas fa-tools"></i>
                            <span class='navbar-full'>  {{__("textes.header_link_administration")}}  </span>
                        </a>
                    </li>
                    @endlevel
                </ul>
                <hr>
                @if(\Illuminate\Support\Facades\Auth::user())
                    <a class="nav-link navbar-text" href="{{ route('compte.index') }}">
                        <i class="fas fa-user"></i>
                        <span class='navbar-full'> {{__("textes.header_link_compte")}}  </span>
                    </a>

                    <a href="{{route('auth.logout') }}" class="nav-link navbar-text">
                        <span class="navbar-full"><i class="fas fa-sign-out-alt"></i> {{__("textes.header_link_deconnexion")}} </span>
                    </a>
                @else
                    <a class="nav-link navbar-text" href="{{ route('login') }}">
                        <i class="fas fa-user"></i>
                        <span class='navbar-full'> {{__("textes.header_link_connexion")}}  </span>
                    </a>
                    <a class="nav-link navbar-text" href="{{ route('register') }}">
                        <i class="fas fa-user-plus"></i>
                        <span class='navbar-full'> {{__("textes.header_link_inscrire")}}  </span>
                    </a>
                @endif
            </div>
        </header>


        <main class="main-content pb-5">
            @yield('content')
        </main>

        <!-- Footer du site -->
        <footer class="footer footer-color mt-auto footer-perso">
            <!-- div contenant l'ensemble du texte du footer en version PC -->
            <div class="footer-full align-items-center justify-content-between">
                <!-- Partie gauche du footer -->
                <div class="footer-padding-left">
                    <label>
                        <select class="form-select form-select-sm mx-2 select-style" style="width: 120px;"
                                onchange="window.location.href='/locale/'+this.value">
                            @foreach($langues as $langue)
                                @php($parametres['lang'] = $langue->langue)
                                <option @if($langue->langue == App::getLocale()) selected @endif value="{{$langue->langue}}">
                                    {{ __('textes.footer_btn_lang_' . $langue->langue) }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <a class="mx-xl-4 mx-2 footer-info"
                       href="{{ route('mentions.index') }}"> {{ __("textes.footer_mentions") }} </a>
                    <a class="mx-xl-4 mx-2 footer-info"
                       href="{{ route('politique.index') }}"> {{ __("textes.footer_confidentialité") }} </a>
                    <a class="mx-xl-4 mx-2 footer-info"
                       href="{{ route('contact.index') }}"> {{ __("textes.footer_contacte") }} </a>
                </div>
                <!-- Partie droite du footer -->
                <div class="pe-5 pe-phone footer-info">
                    <a> © {{date("Y")}} {{ __("textes.footer_realisation") }} </a>
                </div>
            </div>
        </footer>
    </body>
</html>
