@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Новый магазин</h1>
        <hr>
        <form action="{{ route('shop.store') }}" method="POST">

            {{ csrf_field() }}

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
                <label for="name">URL</label>
                <input type="text"
                       class="form-control"
                       name="url"
                       value="{{ old('url') }}">

                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-right">Создать</button>
                </div>
            </div>
        </form>
    </div>
@endsection
