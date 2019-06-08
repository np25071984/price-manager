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
            <div class="col-6">
                <h1>{{ $contractor->name }}</h1>
            </div>
            <div class="col-6">
                <a href="{{ route('contractor.upload_form', $contractor->id) }}"><button type="button" class="btn btn-link float-right">Згарузить прайс поставщика</button></a>
                <a href="{{ route('contractor.deleted_items', $contractor->id) }}"><button type="button" class="btn btn-link float-right">Удаленные товары</button></a>
            </div>
        </div>

        <table-component api-link="{{ $apiLink }}"></table-component>

    </div>
@endsection
