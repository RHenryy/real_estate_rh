<article>
    <div class="detail-container">
        <div class="slider">
            @if (count($image_src) > 1)
                <i class="fa-solid fa-circle-arrow-left prev" aria-label="Previous image"></i>
                <i class="fa-solid fa-circle-arrow-right next" aria-label="Next image"></i>
            @endif
            @foreach ($image_src as $number => $src)
                <div class="slide {{ $number === 1 ? 'active' : 'd-none' }}" id="slide_{{ $number }}">
                    <img src="{{ $src }}" alt="{{ $property->title }}_{{ $number }}"
                        id="image_{{ $number }}">
                </div>
            @endforeach
        </div>
        <div class="detail-info-box">
            <h2>{{ $property->title }}</h2>
            <p>Reference : {{ $property->reference }}</p>
            <div class="detail-flex-info">
                <p>{{ ucfirst($property->type) }}</p>
                <p>{{ ucfirst($property->city) }} ({{ $property->zip }})</p>
            </div>
            <div class="detail-flex-info">
                <p>{{ number_format($property->price, 0, ',', ' ') }} €</p>
                <p>{{ $property->offer }}</p>
            </div>
            <div class="info-row">
                <p><i class="fa-solid fa-house"></i> {{ number_format($property->int_surface, 0, ',', ' ') }}m²</p>
                @if ((int) $property->ext_surface !== 0)
                    <p><i class="fa-solid fa-tree"></i> {{ number_format($property->ext_surface, 0, ',', ' ') }}m²</p>
                @endif
                <p><i class="fa-solid fa-bed"></i> {{ $property->rooms }} rooms</p>
            </div>
        </div>
    </div>
    <div class="description-box">
        <h3>Description</h3>
        <p>{{ $property->description }}</p>
    </div>
</article>
