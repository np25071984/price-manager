@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Новый товар</h1>
        <hr>
        <form action="{{ route('item.store') }}" method="POST">

            {{ csrf_field() }}

            <div class="form-group required">
                <label for="name">Бренд</label>
                <select name="brand_id"
                        class="form-control{{ $errors->has('brand_id') ? ' is-invalid' : '' }}">
                    <option value="" disabled{{  old('brand_id') ? '' : ' selected' }}>Выберите бренд</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}"{{  old('brand_id') === $brand->id ? ' selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('brand_id') }}</div>
            </div>

            <div class="form-group required">
                <label for="name">Артикул</label>
                <input type="text"
                       class="form-control{{ $errors->has('article') ? ' is-invalid' : '' }}"
                       name="article"
                       required
                       value="{{ old('article') }}">

                <div class="invalid-feedback">{{ $errors->first('article') }}</div>
            </div>

            <div class="form-group required">
                <label for="name">Название</label>
                <input type="text"
                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                       name="name"
                       required
                       value="{{ old('name') }}">

                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            </div>

            <div class="form-group">
                <label for="name">Цена</label>
                <input type="text"
                       class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}"
                       name="price"
                       value="{{ old('price') }}">

                <div class="invalid-feedback">{{ $errors->first('price') }}</div>
            </div>

            <div class="form-group">
                <label for="name">Остаток</label>
                <input type="text"
                       class="form-control{{ $errors->has('stock') ? ' is-invalid' : '' }}"
                       name="stock"
                       value="{{ old('stock') }}">

                <div class="invalid-feedback">{{ $errors->first('stock') }}</div>
            </div>


            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-right">Создать</button>
                </div>
            </div>
        </form>
    </div>
@endsection
