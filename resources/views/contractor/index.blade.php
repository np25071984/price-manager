@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('contractor.create') }}"><button type="button" class="btn btn-link float-right">Добавить поставщика</button></a>
            </div>
        </div>

        <table class="table">
            <thead>
                <th>Название поставщика</th>
                <th class="text-center">Функции</th>
            </thead>
            <tbody>
            @foreach ($contractors as $contractor)
                <tr>
                    <td> {{ $contractor->name }}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('contractor.show', $contractor->id) }}" class="btn btn-primary" role="button">
                                Показать
                            </a>
                            <a href="{{ route('contractor.edit', $contractor->id) }}" class="btn btn-primary" role="button">
                                Изменить
                            </a>
                        </div>
                        <form class="d-inline"
                              action="{{ route('contractor.destroy', $contractor->id) }}"
                              method="POST"
                              onsubmit="return confirm('Вы уверены что хотите удалить поставщика?');">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="submit" class="btn btn-danger" value="Удалить"/>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $contractors->links() }}

    </div>
@endsection