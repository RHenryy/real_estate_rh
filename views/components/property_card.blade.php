<div class="card-containers">
    @foreach ($properties as $property)
        @if ($property->image !== '')
            <article class="property-card">
                <div class="property-card-image">
                    <a href="/properties/detail/{{ $property->property_id }}" title="See details">
                        <img src="{{ $property->image }}" alt="{{ $property->title }}"></a>
                    <span class="price-tag">{{ number_format($property->price, 0, ',', ' ') }} €</span>
                </div>
                <div class="property-content">
                    <div class="title">
                        <h3>{{ $property->title }}</h3>
                    </div>
                    @if ($pagename !== 'home')
                        <div class="type">
                            <p>Ref : {{ $property->reference }}</p>
                            <p>{{ $property->city }} ({{ $property->zip }})</p>
                        </div>
                    @endif
                    <div class="type">
                        <p>{{ ucwords($property->type) }}</p>
                        <p>{{ ucfirst($property->offer) }}</p>
                    </div>
                    <div class="info-row">
                        <p><i class="fa-solid fa-house"></i> {{ number_format($property->int_surface, 0, ',', ' ') }}m²
                        </p>
                        @if ((int) $property->ext_surface !== 0)
                            <p><i class="fa-solid fa-tree"></i>
                                {{ number_format($property->ext_surface, 0, ',', ' ') }}m²</p>
                        @endif
                        <p><i class="fa-solid fa-bed"></i> {{ $property->rooms }} rooms</p>
                    </div>
                    @if (!isAuthorized(null, ['manager', 'agent']))
                        <div class="agency-info">
                            <a href="/agencies/detail/{{ $property->agency_id }}" title="See agency page">
                                <p>Agency {{ $property->agency_name }}</p>
                            </a>
                        </div>
                    @endif
                    @if (isAuthorized(null, ['manager', 'agent']) &&
                            $pagename !== 'home' &&
                            $_SESSION['agency_id'] === (int) $property->agency_id)
                        <div class="button">
                            <a href="/properties/edit/{{ $property->property_id }}" title="Edit property">Edit
                                property</a>
                        </div>
                    @else
                        <div class="button">
                            <a href="/properties/detail/{{ $property->property_id }}" title="See details">See
                                details</a>
                        </div>
                    @endif
                </div>
            </article>
        @endif
    @endforeach
</div>
