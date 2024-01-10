<div class="card-containers">
    @foreach ($agencies as $agency)
        @if ($agency->image !== '')
            <article class="property-card">
                <div class="property-card-image">
                    <a href="agencies/detail/{{ $agency->agency_id }}" title="See details">
                        <img src="{{ $agency->image }}" alt="{{ $agency->title }}"></a>
                </div>
                <div class="property-content">
                    <div class="agency-title">
                        <p>{{ $agency->name }}</p>
                    </div>
                    @if (isAuthorized(null, ['manager']) && isset($_SESSION['agency_id']) && $_SESSION['agency_id'] === $agency->agency_id)
                        <div class="button">
                            <a href="agencies/edit/{{ $agency->agency_id }}" title="Edit agency">Edit
                                agency</a>
                        </div>
                    @else
                        <div class="button">
                            <a href="agencies/detail/{{ $agency->agency_id }}" title="See details">See details</a>
                        </div>
                    @endif
                </div>
            </article>
        @endif
    @endforeach
</div>
