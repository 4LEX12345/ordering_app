@extends('adminlte::page')
@section('content')
    <div id="app">
        <dashboard-component
        :collection_month="{{json_encode($collection_month)}}"
        ></dashboard-component>
    </div>
@stop


@section('js')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@stop