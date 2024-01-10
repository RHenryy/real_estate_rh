@extends('layouts.layout')
@section('content')
    <div class="wrapper">
        <section class="agencies">
            <h1>Agencies</h1>
            @component('components.agency_card')
            @endcomponent
        </section>
    </div>
@endsection
