@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="alert alert-secondary" role="alert">
            <p>Идет обработка файла. Вы сможете продолжить работу c <i>{{ $owner }}</i> после завершения процесса обработки.</p>
            <p>Статус обработки: <strong>{{ $job->message }}</strong></p>
        </div>
    </div>
@endsection
