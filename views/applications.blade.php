@extends('layouts.layout')
@section('content')
    <h1>Pending applications</h1>
    <div class="cards-wrapper">
        @if (isset($users) && !empty($users))
            @component('components.user_card_application')
            @endcomponent
        @else
            <div class="empty-users">
                <h2>No pending applications at this time.</h2>
            </div>
        @endif
    </div>
@endsection
