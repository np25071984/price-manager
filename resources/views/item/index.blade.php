@extends('layouts.app')

@section('content')
    <div class="container">
        @if($job && $job->hasError())
            <div class="alert alert-danger" role="alert">
                <p>В процессе обработки прайса прозошла <strong>ошибка</strong>!</p>
                <code>{{ $job->message }}</code>
            </div>
        @endif

        <div class="row">
            <div class="col-6">
                @if ($price)
                    <a target="_blank" href="{{ route('item.download') }}"><button type="button" class="btn btn-link">{{ $price }}</button></a>
                @else
                    Прайс не сгенерирован
                @endif
            </div>
            <div class="col-6">
                <a href="{{ route('item.generate') }}"><button type="button" class="btn btn-link float-right">Сгенерировать новый прайс</button></a>
                <a href="{{ route('item.upload_form') }}"><button type="button" class="btn btn-link float-right">Згарузить прайс</button></a>
                <a href="{{ route('item.create') }}"><button type="button" class="btn btn-link float-right">Добавить товар</button></a>
            </div>
        </div>

        <tab-component item-api-link="{{ $itemApiLink }}" contractor-api-link="{{ $contractorApiLink }}"></tab-component>

    </div>
@endsection