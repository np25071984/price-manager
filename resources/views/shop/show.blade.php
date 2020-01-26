@extends('layouts.app')

@section('content')

    <div class="container">

        @if ($job)
            @if ($job->hasError())
                <div class="alert alert-danger" role="alert">
                    <p>В процессе обработки прайса прозошла <strong>ошибка</strong>!</p>
                    <code>{{ $job->message }}</code>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    {{ $job->message }}
                </div>
            @endif
        @endif

        <h1>Все товары магазина {{ $shop->name }}</h1>

        <div class="row">
            <div class="col-6">
                @if ($price)
                    <a target="_blank" href="{{ route('shop.download', $shop->id) }}"><button type="button" class="btn btn-link">{{ $price }}</button></a>
                @else
                    Прайс не сгенерирован
                @endif
            </div>
            <div class="col-6">
                <a href="{{ route('shop.generate', $shop->id) }}"><button type="button" class="btn btn-link float-right">Сгенерировать новый прайс</button></a>
            </div>
        </div>

        <table-component api-link="{{ $apiLink }}"></table-component>

    </div>
@endsection
