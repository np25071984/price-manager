@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Просмотр бренда</h1>
        <hr>

        <div class="row">
            <div class="col-4 text-right">
                <strong>Название</strong>
            </div>
            <div class="col-8">{{ $brand->name }}</div>
        </div>

    </div>
@endsection
