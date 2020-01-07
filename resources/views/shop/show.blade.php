@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Все товары магазина {{ $shop->name }}</h1>

        <table-component api-link="{{ $apiLink }}"></table-component>

    </div>
@endsection
