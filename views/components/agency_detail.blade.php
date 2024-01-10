<article class="agency-detail-container">
    <div class="agency-image">
        <img src="{{ $agency->image }}" alt="{{ $agency->name }}">
    </div>
    <div class="detail-info-box">
        <p><i class="fa-solid fa-map-location-dot"></i> {{ ucwords($agency->address) }} {{ ucfirst($agency->city) }}
            ({{ $agency->zip }})</p>
        <p><i class="fa-solid fa-phone"></i> <a
                href="tel:{{ $agency->phone }}">{{ implode(' ', str_split($agency->phone, 2)) }}</a></p>
        <p><i class="fa-solid fa-envelope"></i> <a href="mailto:{{ $agency->email }}">{{ $agency->email }}</p></a>
        <div class="detail-flex-info-agency">
            @if ($agency->house_count > 0)
                <p>{{ $agency->house_count }} <i class="fa-solid fa-house fa-2x"></i></p>
            @endif
            @if ($agency->appartment_count > 0)
                <p>{{ $agency->appartment_count }} <i class="fa-solid fa-building fa-2x"></i></p>
            @endif
            @if ($agency->land_count > 0)
                <p>{{ $agency->land_count }} <i class="fa-solid fa-panorama fa-2x"></i></p>
            @endif
        </div>
        <div class="button">
            @if (isAuthorized(null, ['manager']) &&
                    isset($_SESSION['agency_id']) &&
                    $_SESSION['agency_id'] === (int) $agency->agency_id)
                <a href="/agencies/edit/{{ $agency->agency_id }}" title="Edit agency">Edit agency</a>
            @elseif(!isAuthorized(null, ['agent']))
                <a href="/properties/agency/{{ $agency->agency_id }}" title="See agency's properties">See
                    properties</a>
            @endif
        </div>
    </div>
</article>
