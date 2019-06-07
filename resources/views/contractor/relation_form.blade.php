@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Установка связи между товарами</h1>
        <hr>
        <form action="{{ route('contractor.relation_update', [$contractor->id, $contractorItem->id]) }}" method="POST">

            {{ csrf_field() }}

            <h2>Товар поставщика:</h2>
            <div class="row">
                <div class="col-4 text-right">
                    <strong>Поставщик</strong>
                </div>
                <div class="col-8">{{ $contractor->name }}</div>
            </div>

            <div class="row">
                <div class="col-4 text-right">
                    <strong>Артикул</strong>
                </div>
                <div class="col-8">
                    @if ($contractor->config['col_article'])
                        {{ $contractorItem->article }}
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-4 text-right">
                    <strong>Название</strong>
                </div>
                <div class="col-8">{{ $contractorItem->name }}</div>
            </div>

            <div class="row">
                <div class="col-4 text-right">
                    <strong>Цена</strong>
                </div>
                <div class="col-8">{{ $contractorItem->price }}</div>
            </div>

            <h2>Собственный товар:</h2>

            <div class="form-group row">
                <label for="article" class="col-4 col-form-label text-right">Артикул</label>
                <div class="col-8">
                    <input type="text"
                           class="form-control{{ $errors->has('article') ? ' is-invalid' : '' }}"
                           name="article"
                           id="article"
                           readonly="readonly"
                           value="{{ $contractorItem->relatedItem ? $contractorItem->relatedItem->article : null }}">

                    <div class="invalid-feedback">{{ $errors->first('article') }}</div>
                </div>
            </div>

            <div class="form-group row">
                <label for="article" class="col-4 col-form-label text-right">Название</label>
                <div class="col-8">
                    <input type="text"
                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                           name="name"
                           id="name"
                           readonly="readonly"
                           value="{{ $contractorItem->relatedItem ? $contractorItem->relatedItem->name : null }}">

                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-right">Сохранить</button>
                </div>
            </div>

            <table-component api-link="{{ $apiLink }}"></table-component>

        </form>
    </div>
@endsection