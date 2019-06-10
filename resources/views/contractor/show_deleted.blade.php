@extends('layouts.app')

@section('content')

    <div class="container">
        @if($contractor->job && $contractor->job->hasError())
            <div class="alert alert-danger" role="alert">
                <p>В процессе обработки прайса прозошла <strong>ошибка</strong>!</p>
                <code>{{ $contractor->job->message }}</code>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <h1>Удаленные товары поставщика {{ $contractor->name }}</h1>
            </div>
        </div>

        <table-component api-link="{{ $apiLink }}"></table-component>

    </div>
@endsection
