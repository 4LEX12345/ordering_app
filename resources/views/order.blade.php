@extends('adminlte::page')
@section('content')
    <div id="app">
        <order-component></order-component>
    </div>
@stop


@section('js')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@stop