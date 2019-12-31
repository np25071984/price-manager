@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Все товары группы {{ $group->name }}</h1>

        <table-component api-link="{{ $apiLink }}"></table-component>

    </div>
@endsection
