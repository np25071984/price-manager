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
                       name="price"
                       value="{{ $shopItem->price }}">

                <div class="invalid-feedback">{{ $errors->first('price') }}</div>
            </div>


            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-right">Изменить</button>
                </div>
            </div>
        </form>
    </div>
@endsection
