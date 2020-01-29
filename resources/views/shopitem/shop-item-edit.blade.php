@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Редактирование товара в магазине</h1>
        <hr>
        <form action="{{ route('shop.item.update', $shopItem->id) }}" method="POST">

            {{ csrf_field() }}

            <div class="form-group">
                <label for="price">Базовая цена</label>
                <input type="text"
                       class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}"
                       id="price"
                       name="price"
                       value="{{ $shopItem->price }}">

                <div class="invalid-feedback">{{ $errors->first('price') }}</div>
            </div>

            <div class="form-group">
                <label for="discount_price">Цена со скидкой</label>
                <input type="text"
                       class="form-control{{ $errors->has('discount_price') ? ' is-invalid' : '' }}"
                       id="discount_price"
                       name="discount_price"
                       value="{{ $shopItem->discount_price }}">

                <div class="invalid-feedback">{{ $errors->first('discount_price') }}</div>
            </div>


            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-right">Изменить</button>
                </div>
            </div>
        </form>
    </div>
@endsection
