@extends('layouts.layout')
@section('content')
    <div class="wrapper">
        <h1>{{ $property->title }}</h1>
        @component('components.property_detail')
        @endcomponent
    </div>
@endsection
