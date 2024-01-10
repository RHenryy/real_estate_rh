@extends('layouts.layout')
@section('content')
    <div class="wrapper">
        <h1>{{ $editProperty->title }}</h1>
        @component('components.edit_property_form')
        @endcomponent
    </div>
@endsection
