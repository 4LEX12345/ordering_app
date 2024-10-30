@extends('adminlte::page')
@section('content')
    <div id="app">
        <system-parameter-component
        ></system-parameter-component>
    </div>
@stop


@section('js')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@stop