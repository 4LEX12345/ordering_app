@extends('adminlte::page')
@section('content')
    <div id="app">
        <product-component></product-component>
    </div>
@stop


@section('js')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@stop