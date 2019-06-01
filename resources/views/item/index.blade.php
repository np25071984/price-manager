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

        <table class="table">
            <thead>
                <th>Артикул</th>
                <th>Бренд</th>
                <th>Название товара</th>
                <th>Цена</th>
                <th>Остаток</th>
                <th class="text-center">Функции</th>
            </thead>
            <tbody>
            @foreach ($items as $item)
                <tr>
                    <td class="text-center"> {{ $item->article }}</td>
                    <td> {{ $item->brand->name }}</td>
                    <td> {{ $item->name }}</td>
                    <td class="text-center"> {{ $item->price }}</td>
                    <td class="text-center"> {{ $item->stock }}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('item.show', $item->id) }}" class="btn btn-primary" role="button">
                                Показать
                            </a>
                            <a href="{{ route('item.edit', $item->id) }}" class="btn btn-primary" role="button">
                                Изменить
                            </a>
                        </div>
                        <form class="d-inline"
                              action="{{ route('item.destroy', $item->id) }}"
                              method="POST"
                              onsubmit="return confirm('Вы уверены что хотите удалить товар?');">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="submit" class="btn btn-danger" value="Удалить"/>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="row justify-content-center">
            {{ $items->links() }}
        </div>

        @if (count($contractorItems))
        <h2>Похожие не связанные товары</h2>
        <table class="table">
            <thead>
            <th>Поставщик</th>
            <th>Название товара</th>
            <th class="text-center">Цена</th>
            <th class="text-center">Функции</th>
            </thead>
            <tbody>
            @foreach ($contractorItems as $item)
                <tr>
                    <td> {{ $item->contractor->name }}</td>
                    <td> {{ $item->name }}</td>
                    <td class="text-center"> {{ $item->price }}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('contractor.relation_form', [$item->contractor->id, $item->id]) }}" class="btn btn-primary" role="button">
                                Добавить связь
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif


    </div>
@endsection