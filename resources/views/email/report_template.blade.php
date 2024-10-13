@extends('layouts.email')
@section('content')
    <div class="content-email">
        <h1 class="title-email">Report de Bug</h1>

        <h5>Provenance :
            <bold>{{$data['email']}}</bold>
        </h5>
        <h5>Sujet :
            <bold>{{$data['sujet']}}</bold>
        </h5>
        <p class="text-email">{{$data['message']}}</p>

    </div>
@endsection

