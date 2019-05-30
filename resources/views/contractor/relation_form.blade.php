@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Установка связи с товаром поставщика {{ $contractor->name }}</h1>
        <hr>
        <form action="{{ route('contractor.relation_update', [$contractor->id, $contractorItem->id]) }}" method="POST">

            {{ csrf_field() }}

            <div class="row">
                <div class="col-4 text-right">
                    <strong>Название товара у поставщика</strong>
                </div>
                <div class="col-8">{{ $contractorItem->name }}</div>
            </div>

            <div class="row">
                <div class="col-4 text-right">
                    <strong>Название в собственном складе</strong>
                </div>
                <div class="col-8" id="own_item">{{ $contractorItem->relatedItem->first() ? $contractorItem->relatedItem->first()->name : null }}</div>
                <input type="hidden" name="item" value="{{ $contractorItem->relatedItem->first() ? $contractorItem->relatedItem->first()->id : null }}" />
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-right">Сохранить</button>
                </div>
            </div>

            <table class="table">
                <thead>
                <th>Артикул</th>
                <th>Бренд</th>
                <th>Название товара</th>
                <th>Цена</th>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td class="text-center">{{ $item->article }}</td>
                        <td>{{ $item->brand->name }}</td>
                        <td><a href="#" onclick="
                                    document.getElementById('own_item').innerText = this.innerText;
                                    document.getElementsByName('item')[0].value = {{ $item->id }};">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td class="text-center"> {{ $item->price }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="row justify-content-center">
                {{ $items->links() }}
            </div>

        </form>
    </div>
@endsection