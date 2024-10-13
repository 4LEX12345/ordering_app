@extends('adminlte::page')
@section('content')
    <div id="app">
        <brand-component></brand-component>
    </div>
@stop


@section('js')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@stop