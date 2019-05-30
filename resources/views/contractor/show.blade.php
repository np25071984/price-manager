@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-6">
                <h1>{{ $contractor->name }}</h1>
            </div>
            <div class="col-6">
                <a href="{{ route('contractor.price_upload_form', $contractor->id) }}"><button type="button" class="btn btn-link float-right">Згарузить прайс поставщика</button></a>
            </div>
        </div>

        <table class="table">
            <thead>
            <th>Название товара</th>
            <th>Связь</th>
            <th>Цена</th>
            <th class="text-center">Функции</th>
            </thead>
            <tbody>
            @foreach ($items as $item)
                <tr>
                    <td> {{ $item->name }}</td>
                    <td>
                        @if ($item->relatedItem->first())
                            {{ $item->relatedItem->first()->name }}
                        @else
                            не связана
                        @endif
                    </td>
                    <td class="text-center"> {{ $item->price }}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('item.show', $item->id) }}" class="btn btn-primary" role="button">
                                Изменить связь
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="row justify-content-center">
            {{ $items->links() }}
        </div>
    </div>
@endsection
