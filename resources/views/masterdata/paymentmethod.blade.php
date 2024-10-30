@extends('adminlte::page')
@section('content')
    <div id="app">
        <payment-method-component></payment-method-component>
    </div>
@stop


@section('js')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@stop