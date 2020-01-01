@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Просмотр товара</h1>
        <hr>

        <div class="row">
            <div class="col-4 text-right">
                <strong>Бренд</strong>
            </div>
            <div class="col-8">{{ $item->brand->name }}</div>
        </div>

        <div class="row">
            <div class="col-4 text-right">
                <strong>Группа</strong>
            </div>
            <div class="col-8">{{ $item->group->name }}</div>
        </div>

        <div class="row">
            <div class="col-4 text-right">
                <strong>Артикул</strong>
            </div>
            <div class="col-8">{{ $item->article }}</div>
        </div>

        <div class="row">
            <div class="col-4 text-right">
                <strong>Название</strong>
            </div>
            <div class="col-8">{{ $item->name }}</div>
        </div>

        <div class="row">
            <div class="col-4 text-right">
                <strong>Цена</strong>
            </div>
            <div class="col-8">{{ $item->price }}</div>
        </div>

        <div class="row">
            <div class="col-4 text-right">
                <strong>Остаток</strong>
            </div>
            <div class="col-8">{{ $item->stock }}</div>
        </div>

        <h2 class="mt-5">Предложения поставщиков</h2>

        <table-component api-link="{{ $apiLink }}"></table-component>

    </div>
@endsection
