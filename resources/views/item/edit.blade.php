@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Редактирование товара</h1>
        <hr>
        <form action="{{ route('item.update', $item->id) }}" method="POST">

            {{ csrf_field() }}

            <input type="hidden" name="_method" value="PUT">

            <div class="form-group">
                <label for="shop_id[]">Магазины</label>
                <select name="shop_id[]" class="form-control" size="3" multiple>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->id }}"{{  in_array($shop->id, $item->shopIds()) ? ' selected' : '' }}>{{ $shop->name }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('shop_id') }}</div>
            </div>

            <div class="form-group required">
                <label for="brand_id">Бренд</label>
                <select name="brand_id"
                        class="form-control{{ $errors->has('brand_id') ? ' is-invalid' : '' }}">
                    <option value="" disabled>Выберите бренд</option>
                    @foreach ($brands as $brand)
                        <option {{ $item->brand->id === $brand->id ? 'selected ' : '' }}value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('brand_id') }}</div>
            </div>

            <div class="form-group">
                <label for="county_id">Страна</label>
                <select name="country_id"
                        class="form-control{{ $errors->has('country_id') ? ' is-invalid' : '' }}">
                    <option value="" {{ $item->country ? '' : 'selected ' }} disabled>Выберите страну</option>
                    @foreach ($countries as $country)
                        <option {{ ($item->country && $item->country->id === $country->id) ? 'selected ' : '' }}value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('country_id') }}</div>
            </div>

            <div class="form-group">
                <label for="group_id">Группа</label>
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
                <label for="type">Тип</label>
                <select name="type" class="form-control">
                    <option value="" disabled{{  old('type') ? '' : ' selected' }}>Выберите тип парфюмерной продукции</option>
                    @foreach ($types as $type)
                        <option value="{{ $type }}"{{  $item->type === $type ? ' selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('type') }}</div>
            </div>

            <div class="form-group required">
                <label for="article">Артикул</label>
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

            <div class="form-group">
                <label for="volume">Объем</label>
                <input type="text"
                       class="form-control{{ $errors->has('volume') ? ' is-invalid' : '' }}"
                       name="volume"
                       value="{{ $item->volume }}">

                <div class="invalid-feedback">{{ $errors->first('volume') }}</div>
            </div>

            <div class="form-group">
                <label for="stock">Остаток</label>
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
