@extends('adminlte::page')
@section('content')
    <div id="app">
        <product-category-component></product-category-component>
    </div>
@stop


@section('js')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@stop