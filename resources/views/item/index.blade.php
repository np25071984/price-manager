@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('item.price_upload_form') }}"><button type="button" class="btn btn-link float-right">Выгрузить</button></a>
                <a href="{{ route('item.price_upload_form') }}"><button type="button" class="btn btn-link float-right">Згарузить</button></a>
            </div>
        </div>
        <hr />
        items
    </div>
@endsection