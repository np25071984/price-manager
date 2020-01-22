@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Все товары произведенные в {{ $country->name }}</h1>

        <table-component api-link="{{ $apiLink }}"></table-component>

    </div>
@endsection
