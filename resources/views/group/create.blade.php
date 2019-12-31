@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Новая группа</h1>
        <hr>
        <form action="{{ route('group.store') }}" method="POST">

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

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-right">Создать</button>
                </div>
            </div>
        </form>
    </div>
@endsection
