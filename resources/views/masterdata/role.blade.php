@extends('adminlte::page')
@section('content')
    <div id="app">
        <role-component></role-component>
    </div>
@stop


@section('js')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@stop