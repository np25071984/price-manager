@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Редактирование товара</h1>
        <hr>
        <form action="{{ route('item.update', $item->id) }}" method="POST">

            {{ csrf_field() }}

            <input type="hidden" name="_method" value="PUT">

            <div class="form-group">
                <label for="name">Магазины</label>
                <select name="shop_id[]" class="form-control" size="3" multiple>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->id }}"{{  in_array($shop->id, $item->shopIds()) ? ' selected' : '' }}>{{ $shop->name }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('shop_id') }}</div>
            </div>

            <div class="form-group required">
                <label for="name">Бренд</label>
                <select name="brand_id"
                        class="form-control{{ $errors->has('brand_id') ? ' is-invalid' : '' }}">
                    <option value="" disabled>Выберите бренд</option>
                    @foreach ($brands as $brand)
                        <option {{ $item->brand->id === $brand->id ? 'selected ' : '' }}value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('brand_id') }}</div>
            </div>

            <div class="form-group required">
                <label for="name">Группа</label>
                <select name="group_id"
                        class="form-control{{ $errors->has('group_id') ? ' is-invalid' : '' }}">
                    <option value="" disabled>Выберите группу</option>
                    @foreach ($groups as $group)
                        <option {{ $item->group->id === $group->id ? 'selected ' : '' }}value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('group_id') }}</div>
            </div>

            <div class="form-group required">
                <label for="name">Артикул</label>
                <input type="text"
                       class="form-control{{ $errors->has('article') ? ' is-invalid' : '' }}"
                       name="article"
                       required
                       value="{{ $item->article }}">

                <div class="invalid-feedback">{{ $errors->first('article') }}</div>
            </div>

            <div class="form-group required">
                <label for="name">Название</label>
                <input type="text"
                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                       name="name"
                       required
                       value="{{ $item->name }}">

                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            </div>

            <div class="form-group required">
                <label for="name">Цена</label>
                <input type="text"
                       class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}"
                       name="price"
                       value="{{ $item->price }}">

                <div class="invalid-feedback">{{ $errors->first('price') }}</div>
            </div>

            <div class="form-group required">
                <label for="name">Остаток</label>
                <input type="text"
                       class="form-control{{ $errors->has('stock') ? ' is-invalid' : '' }}"
                       name="stock"
                       value="{{ $item->stock }}">

                <div class="invalid-feedback">{{ $errors->first('stock') }}</div>
            </div>


            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-right">Изменить</button>
                </div>
            </div>
        </form>
    </div>
@endsection
