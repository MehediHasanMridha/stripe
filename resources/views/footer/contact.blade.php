@extends('layouts.template')
@section('title'){{ __("textes.footer_contacte") }}@endsection
@section('content')
    <section>
        <div class="container">
            <div class="d-flex align-items-center justify-content-center">
                <div class="container box">
                    <h3 class="text-center">{{ __("textes.footer_contacte") }}</h3><br/>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <button type="button" class="btn close" data-dismiss="alert">×</button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    <form method="post" action="{{url('contact/send')}}">
                        @csrf
                        <div class="form-group mb-2">
                            <label class="mb-1">{{ __("textes.footer_contacte_sujet") }}</label>
                            <input type="text" name="sujet" class="form-control" value=""/>
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-1">{{ __("textes.footer_contacte_message") }}</label>
                            <textarea name="message" class="form-control" rows="8"></textarea>
                        </div>
                        <div class="form-group d-flex justify-content-center">
                            <input type="submit" name="send" class="btn btn-small-plus btn-text"
                                   value="{{ __("textes.footer_contacte_envoyer") }}"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
