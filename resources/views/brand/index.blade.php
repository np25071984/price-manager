@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('brand.create') }}"><button type="button" class="btn btn-link float-right">Добавить бренд</button></a>
            </div>
        </div>

        <table-component api-link="/api/brand"></table-component>

    </div>
@endsection