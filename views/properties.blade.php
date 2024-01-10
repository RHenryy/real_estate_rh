@extends('layouts.layout')
@section('content')
    <div class="wrapper">
        <section class="properties">
            @if (isAuthorized(null, ['manager', 'agent']) && isset($_SESSION['agency_id']) && $_SESSION['agency_id'] === $agency_id)
                <h1>My Properties</h1>
                <h3 class="show-property-form" aria-label="Open property form window"><span>Add a new property</span></h3>
                <div class="property-form-backdrop d-none">
                    <div class="property-form-modal">
                        @component('components.property_form')
                        @endcomponent
                    </div>
                </div>
            @else
                <h1>Properties {{ isset($titleAgency) ? "of Agency $titleAgency" : '' }}</h1>
            @endif
            @if (!empty($properties))
                @component('components.property_card', ['pagename' => 'properties'])
                @endcomponent
            @else
                @if (!isAuthorized(null, ['agent', 'manager']))
                    <p>No properties for sale or rent, apologies.</p>
                @endif
            @endif
        </section>
    </div>
@endsection
