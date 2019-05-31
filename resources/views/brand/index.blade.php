@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('brand.create') }}"><button type="button" class="btn btn-link float-right">Добавить бренд</button></a>
            </div>
        </div>

        <table class="table">
            <thead>
                <th>Название поставщика</th>
                <th class="text-center">Функции</th>
            </thead>
            <tbody>
            @foreach ($brands as $brand)
                <tr>
                    <td> {{ $brand->name }}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('brand.show', $brand->id) }}" class="btn btn-primary" role="button">
                                Показать
                            </a>
                            <a href="{{ route('brand.edit', $brand->id) }}" class="btn btn-primary" role="button">
                                Изменить
                            </a>
                        </div>
                        <form class="d-inline"
                              action="{{ route('brand.destroy', $brand->id) }}"
                              method="POST"
                              onsubmit="return confirm('Вы уверены что хотите удалить бренд?');">
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
            {{ $brands->links() }}
        </div>

    </div>
@endsection