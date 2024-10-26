@extends('adminlte::page')
@section('content')
    <div id="app">
        <!-- <h2 class="p-5">Welcome!</h2> -->
        <dashboard-component></dashboard-component>
    </div>
@stop


@section('js')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@stop