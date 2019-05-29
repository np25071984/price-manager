@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Загрзука собственного прайса</h1>
        <hr>
        <form action="{{ route('item.price_upload') }}" method="POST" enctype="multipart/form-data">

            {{ csrf_field() }}

            <input type="file" name="price" />

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-right">Загрузить</button>
                </div>
            </div>
        </form>
    </div>
@endsection