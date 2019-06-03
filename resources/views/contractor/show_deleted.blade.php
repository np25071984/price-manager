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
            <div class="col-12">
                <h1>Удаленные товары поставщика {{ $contractor->name }}</h1>
            </div>
        </div>

        <table class="table">
            <thead>
            <th>Название товара</th>
            <th>Связь</th>
            <th>Цена</th>
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
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="row justify-content-center">
            {{ $contractorItems->links() }}
        </div>
    </div>
@endsection
