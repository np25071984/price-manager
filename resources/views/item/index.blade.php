@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-12">
                <a href="{{ route('item.upload_form') }}"><button type="button" class="btn btn-link float-right">Згарузить прайс</button></a>
                <a href="{{ route('item.create') }}"><button type="button" class="btn btn-link float-right">Добавить товар</button></a>
            </div>
        </div>

        <tab-component item-api-link="{{ $itemApiLink }}" contractor-api-link="{{ $contractorApiLink }}"></tab-component>

    </div>
@endsection