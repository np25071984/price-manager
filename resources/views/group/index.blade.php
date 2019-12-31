@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h1>Группы</h1>
            </div>
            <div class="col-6">
                <a href="{{ route('group.create') }}"><button type="button" class="btn btn-link float-right">Добавить группу</button></a>
            </div>
        </div>

        <table-component api-link="{{ $apiLink }}"></table-component>

    </div>
@endsection
