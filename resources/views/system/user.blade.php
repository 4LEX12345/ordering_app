@extends('adminlte::page')
@section('content')
    <div id="app">
        <user-component
        :roles="{{ json_encode($roles)}}"
        ></user-component>
    </div>
@stop


@section('js')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@stop