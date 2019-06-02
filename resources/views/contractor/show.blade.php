@extends('layouts.app')

@section('content')

    <div class="container">
        @if($contractor->job && $contractor->job->hasError())
            <div class="alert alert-danger" role="alert">
                <p>В процессе обработки прайса прозошла <strong>ошибка</strong>!</p>
                <code>{{ $contractor->job->message }}</code>
            </div>
        @endif

        <div class="row">
            <div class="col-6">
                <h1>{{ $contractor->name }}</h1>
            </div>
            <div class="col-6">
                <a href="{{ route('contractor.upload_form', $contractor->id) }}"><button type="button" class="btn btn-link float-right">Згарузить прайс поставщика</button></a>
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
            @foreach ($contractorItems as $item)
                <tr>
                    <td> {{ $item->name }}</td>
                    <td>
                        @if ($item->relatedItem)
                            {{ $item->relatedItem->name }}
                        @else
                            не связана
                        @endif
                    </td>
                    <td class="text-center"> {{ $item->price }}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('contractor.relation_form', [$contractor->id, $item->id]) }}" class="btn btn-primary" role="button">
                                Изменить связь
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="row justify-content-center">
            {{ $contractorItems->links() }}
        </div>
    </div>
@endsection
