@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('contractor.create') }}"><button type="button" class="btn btn-link float-right">Добавить поставщика</button></a>
            </div>
        </div>

        <table-component api-link="{{ $apiLink }}"></table-component>

    </div>
@endsection