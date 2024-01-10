@extends('layouts.layout')
@section('content')
    <div class="wrapper">
        <section class="agency">
            <h1>{{ $agency->name }}</h1>
            @component('components.agency_detail')
            @endcomponent
        </section>
    </div>
@endsection
