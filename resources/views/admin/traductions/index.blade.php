@extends('layouts.admin-template')
@section('title'){{__("textes.traductions_page_nom")}}@endsection
@section('content')
    <section>
        <div class="container mb-3 mt-4">
            <div class="text-center mb-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div></div>

                    <h1>{{__("textes.traductions_index_titre")}}</h1>

                    <a href="{{ route('accueil.admin') }}">
                        <button type="button" onclick="window.history.go(-1)"
                                class="btn-close ms-2 text-border-space-right icon-size" aria-label="Close"></button>
                    </a>
                </div>
            </div>

            <hr>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col" class="w-75">{{__("textes.traductions_index_soustitre_langues")}}</th>
                    <th scope="col" class="w-25">{{__("textes.traductions_index_soustritre_avancer")}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($langues as $langue)
                    <tr>
                        <th scope="row"><a href="{{ route('traductions.show', [$langue->id]) }}"
                                           class="text-decoration-none text-color">{{ $langue->id }}</a></th>
                        <td><a href="{{ route('traductions.show', [$langue->id]) }}"
                               class="text-decoration-none text-color">{{ $langue->langue }}</a></td>
                        <td>
                            <span
                                style="color: @if($langue->taux < 20) #9B1B00 @elseif($langue->taux < 50) #CA8D13 @elseif($langue->taux < 80) #A3CA13 @else #48CA13 @endif">{{ $langue->taux }}%</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th scope="col" colspan="3"><a href="{{ route('traductions.create') }}" type="button"
                                                   class="btn btn-color text-white p-2 text-decoration-none">{{__("textes.traductions_index_ajouter")}}</a>
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>
    </section>
@endsection
