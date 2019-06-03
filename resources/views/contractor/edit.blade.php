@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Редактирование товара</h1>
        <hr>
        <form action="{{ route('contractor.update', $contractor->id) }}" method="POST">

            {{ csrf_field() }}

            <input type="hidden" name="_method" value="PUT">

            <div class="form-group required">
                <label for="name">Название</label>
                <input type="text"
                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                       name="name"
                       required
                       value="{{ $contractor->name }}">

                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            </div>

            <fieldset class="form-group">
                <legend>Конфигурация прайса</legend>

                <div class="form-group row">
                    <label for="col_article" class="col-4 col-form-label">Номер колонки с артикулом</label>
                    <div class="col-8">
                        <input type="text"
                               class="form-control{{ $errors->has('col_article') ? ' is-invalid' : '' }}"
                               name="col_article"
                               value="{{ $contractor->config['col_article'] }}">

                        <div class="invalid-feedback">{{ $errors->first('col_article') }}</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="col_name" class="col-4 col-form-label">Номер колонки с названием товара</label>
                    <div class="col-8">
                        <input type="text"
                               class="form-control{{ $errors->has('col_name') ? ' is-invalid' : '' }}"
                               name="col_name"
                               value="{{ $contractor->config['col_name'] }}">

                        <div class="invalid-feedback">{{ $errors->first('col_name') }}</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="col_price" class="col-4 col-form-label">Номер колонки с ценой товара</label>
                    <div class="col-8">
                        <input type="text"
                               class="form-control{{ $errors->has('col_price') ? ' is-invalid' : '' }}"
                               name="col_price"
                               value="{{ $contractor->config['col_price'] }}">

                        <div class="invalid-feedback">{{ $errors->first('col_price') }}</div>
                    </div>
                </div>

            </fieldset>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-right">Изменить</button>
                </div>
            </div>
        </form>
    </div>
@endsection
