@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Новый товар</h1>
        <hr>
        <form action="{{ route('item.store') }}" method="POST">

            {{ csrf_field() }}

            <div class="form-group">
                <label for="shop_id[]">Магазины</label>
                <select name="shop_id[]" class="form-control" size="3" multiple>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->id }}"{{  is_array(old('shop_id')) && in_array($shop->id, old('shop_id'))  ? ' selected' : '' }}>{{ $shop->name }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('shop_id') }}</div>
            </div>

            <div class="form-group required">
                <label for="brand_id">Бренд</label>
                <select name="brand_id"
                        class="form-control{{ $errors->has('brand_id') ? ' is-invalid' : '' }}">
                    <option value="" disabled{{  old('brand_id') ? '' : ' selected' }}>Выберите бренд</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}"{{  old('brand_id') === $brand->id ? ' selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('brand_id') }}</div>
            </div>

            <div class="form-group">
                <label for="county_id">Страна</label>
                <select name="country_id"
                        class="form-control{{ $errors->has('country_id') ? ' is-invalid' : '' }}">
                    <option value="" disabled{{  old('country_id') ? '' : ' selected' }}>Выберите страну</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}"{{  old('country_id') === $country->id ? ' selected' : '' }}>{{ $country->name }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('country_id') }}</div>
            </div>

            <div class="form-group">
                <label for="group_id">Группа</label>
                <select name="group_id" class="form-control">
                    <option value="" disabled{{  old('group_id') ? '' : ' selected' }}>Выберите группу</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}"{{  old('group_id') === $group->id ? ' selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('group_id') }}</div>
            </div>

            <div class="form-group required">
                <label for="type">Тип</label>
                <select name="type"
                        class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}">
                    <option value="" disabled{{  old('type') ? '' : ' selected' }}>Выберите тип парфюмерной продукции</option>
                    @foreach ($types as $type)
                        <option value="{{ $type }}"{{  old('type') === $type ? ' selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('type') }}</div>
            </div>

            <div class="form-group">
                <label for="aroma_id[]">Ароматы</label>
                <select name="aroma_id[]" class="form-control" size="3" multiple>
                    @foreach ($aromas as $aroma)
                        <option value="{{ $aroma->id }}"{{ is_array(old('aroma_id')) && in_array($aroma->id, old('aroma_id'))  ? ' selected' : '' }}>{{ $aroma->name }}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">{{ $errors->first('aroma_id') }}</div>
            </div>

            <div class="form-group required">
                <label for="article">Артикул</label>
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
                <label for="volume">Объем</label>
                <input type="text"
                       class="form-control{{ $errors->has('volume') ? ' is-invalid' : '' }}"
                       name="volume"
                       value="{{ old('volume') }}">

                <div class="invalid-feedback">{{ $errors->first('volume') }}</div>
            </div>

            <div class="form-group">
                <label for="stock">Остаток</label>
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
