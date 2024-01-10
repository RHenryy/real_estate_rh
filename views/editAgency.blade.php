@extends('layouts.layout')
@section('content')
    <div class="wrapper">
        <section class="editAgency">
            @component('components.agency_form')
            @endcomponent
        </section>
    </div>
@endsection
