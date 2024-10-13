@extends('layouts.admin-template')
@section('title'){{__("textes.traductions_page_nom")}}@endsection
@section('content')
    <section id="top">
        <div class="container-fluid container-xl">
            <div class="mb-3 mt-4">
                <div class="d-flex align-items-center justify-content-between phone-margin">
                    <div class="div-padding"></div>

                    <!-- Titre de niveau 2 -->
                    <h2 class="text-center text-create-size title-padding mx-3">"{{ $infos['langue'] }}"</h2>

                    <!-- Affiche une îcone qui ramène l'utilisateur en arrière -->
                    <a href="{{ route('traductions.index') }}"
                       class="btn-close ms-2 title-show-size text-border-space-right icon-size" aria-label="Close"></a>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <a href="{{ route('traductions.edit', [$infos['id'], 1]) }}"
                   class="btn btn-admin btn-color text-white fw-bold me-4">{{__("textes.traductions_show_edit")}}</a>
            <!--<form action="{{ route('traductions.destroy', [$infos['id']]) }}" method="POST">
                    @csrf
            @method('DELETE')
                <button type="submit" class="btn btn-admin btn-color text-white mt-3 fw-bold">{{__("textes.traductions_show_supp")}}</button>
                </form>-->
            </div>

            <!-- Créer un formulaire qui va permettre de chercher des formules dans la liste en appelant la page formules.search -->
            <form action="{{ route('traductions.search', [$infos['id']]) }}" class="phone-margin d-flex mb-5"
                  method="GET">

                <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui être transmis par la suite au code -->
                <input class="form-control me-2 list-search" type="text" name="search"
                       placeholder="{{__('textes.traductions_show_search_placeholder')}}"
                       @if(request('search')) value="{{ request('search') }}" @endif autocomplete="off"
                       aria-label="Search">
                <!-- Bouton affichant une icone et permettant de valider la recherche -->
                <button class="btn btn-small" type="submit"><i class="fas fa-search icon-size icon-white"></i></button>

                <!-- Si l'utilisateur a effectué une recherche -->
            @if(request('search'))
                <!-- Affiche une icone qui ramène l'utilisateur vers la page contenant la liste de tous les ingrédients -->
                    <a href="{{ route('traductions.show', [$infos['id']]) }}">
                        <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                    </a>
                @endif

            </form>

            <hr>

            <div class="d-flex">
                <p class="text-size">
                    {{__("textes.traductions_show_tauxtrad")}}
                    <span
                        style="color: @if($taux < 20) #9B1B00 @elseif($taux < 50) #CA8D13 @elseif($taux < 80) #A3CA13 @else #48CA13 @endif">
                        {{ $taux }}%
                    </span>
                </p>

                <progress class="mt-1 ms-4 w-25 text-size" max="100" value="{{ $taux }}"></progress>
            </div>
            <table class="table table-striped table-hover table-bordered text-size">
                <thead>
                <tr>
                    <th scope="col" class="th-legend traduction-code-width">
                        <strong>{{__("textes.traductions_show_code")}}</strong></th>
                <!--<th scope="col" class="th-legend traduction-cle-width"><strong>{{__("textes.traductions_show_cle")}}</strong></th>-->
                    <th scope="col" class="th-legend traduction-texte-width">
                        <strong>{{__("textes.traductions_show_texte")}}</strong></th>
                </tr>
                </thead>
                <tbody>
                @foreach($traduction as $page => $textes)
                    <tr>
                        <th class="text-center fw-bold th-page" colspan="3" scope="row"><h4>{{ $page }}</h4></th>
                    </tr>
                    @foreach($textes as $key => $texte)
                        <tr>
                            <th scope="row">[{{stristr('#', $zoulou[$key]) ? substr($zoulou[$key], 1) : $zoulou[$key]}}
                                ]
                            </th>
                        <!--<th scope="row">{{ $key }}</th>-->
                            <td>{{ $texte }}</td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>

            <div class="arrow-scroll">
                <a><i class="fas fa-arrow-circle-up icon-color"></i></a>
            </div>
        </div>

        <script type="text/javascript">
            jQuery(function () {
                $(function () {
                    $(window).scroll(function () {
                        if ($(this).scrollTop() > 4000) {
                            $('.arrow-scroll')
                                .css('right', '30px')
                                .click(function () {
                                    $('html, body').animate({scrollTop: -100}, 1);
                                });
                        } else {
                            $('.arrow-scroll').removeAttr('style');
                        }
                    });
                });
            });
        </script>
    </section>
@endsection
