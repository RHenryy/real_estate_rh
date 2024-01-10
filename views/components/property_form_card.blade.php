@foreach ($properties as $property)
    <p>{{ $property->address }} - {{ $property->city }} - {{ $property->zip }} - Nb de chambres {{ $property->rooms }}
    </p>
    <div style="width: 25%">
        @foreach ($image_src as $src)
            <img style="width:250px" src="{{ $src }}" alt="{{ $property->title }}">
        @endforeach
    </div>
@endforeach
