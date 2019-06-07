@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h1>Бренды</h1>
            </div>
            <div class="col-6">
                <a href="{{ route('brand.create') }}"><button type="button" class="btn btn-link float-right">Добавить бренд</button></a>
            </div>
        </div>

        <table-component api-link="{{ $apiLink }}"></table-component>

    </div>
@endsection