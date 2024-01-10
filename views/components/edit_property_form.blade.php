<div class="form-title">
    <h2>Edit property</h2>
    <i aria-label="Close property form window" class="fa-solid fa-circle-xmark fa-2x close-modal"></i>
</div>
<div class="class-form property">
    <form action="/properties/update/{{ $editProperty->property_id }}" method="post" id="connection-form"
        enctype="multipart/form-data">
        <div class="input-group">
            <p>Current images</p>
        </div>
        @php
            $i = 0;
        @endphp
        @foreach ($image_src as $src)
            <div class="input-group">
                @php
                    $i++;
                @endphp
                <label for="input_image_{{ $i }}">
                    <img width="100px" height="100px;" src="{{ $src }}?{{ rand(1, 100) }}"
                        alt="{{ $editProperty->title }}" id="image_{{ $i }}">
                </label>
                <input type="file" name="image_{{ $i }}" id="input_image_{{ $i }}">
                <a href="/properties/deleteimg/{{ $editProperty->property_id }}-{{ $i }}"
                    title="Delete image {{ $i }}"><i aria-label="Close property form window"
                        class="fa-solid fa-circle-xmark fa-2x"></i></a>
            </div>
        @endforeach
        <div class="input-group addFile">
            <p>Add image</p>
        </div>
        <div class="input-group">
            <label for="reference">Reference :</label>
            <input type="text" name="property_reference" id="reference"
                value="@if (isset($editProperty->reference)) {{ $editProperty->reference }} @endif" required>
        </div>
        <div class="input-group">
            <label for="address">Complete Address :</label>
            <input type="text" name="property_address" id="address"
                @if (isset($editProperty->address) && isset($editProperty->city) && isset($editProperty->zip)) value="{{ $editProperty->address }} {{ $editProperty->zip }} {{ $editProperty->city }}" @endif
                required>
            <div class="custom-select d-none">
                <ul></ul>
            </div>
        </div>
        <div class="input-group">
            <label for="street">Street :</label>
            <input type="text" name="property_street" id="street"
                @if (isset($editProperty->address)) value="{{ $editProperty->address }}" @endif required>
        </div>
        <div class="input-group">
            <label for="city">City :</label>
            <input type="text" name="property_city" id="city"
                @if (isset($editProperty->city)) value="{{ $editProperty->city }}" @endif required>
        </div>
        <div class="input-group">
            <label for="zipcode">Zipcode :</label>
            <input type="text" name="property_zipcode" id="zipcode"
                @if (isset($editProperty->zip)) value="{{ $editProperty->zip }}" @endif required>
        </div>
        <div class="input-group">
            <label for="offer">Offer :</label>
            <select name="property_offer" id="">
                <option value=""></option>
                <option value="sale" @if (isset($editProperty->offer) && $editProperty->offer === 'For sale') selected @endif>For sale</option>
                <option value="rent" @if (isset($editProperty->offer) && $editProperty->offer === 'For rent') selected @endif>For rent</option>
            </select>
        </div>
        <div class="input-group">
            <label for="type">Property type :</label>
            <select name="property_type" id="">
                <option value=""></option>
                <option value="house" @if (isset($editProperty->type) && $editProperty->type === 'house') selected @endif>House</option>
                <option value="appartment" @if (isset($editProperty->type) && $editProperty->type === 'appartment') selected @endif>Appartment</option>
                <option value="studio" @if (isset($editProperty->type) && $editProperty->type === 'studio') selected @endif>Studio flat</option>
                <option value="land" @if (isset($editProperty->type) && $editProperty->type === 'land') selected @endif>Land</option>
            </select>
        </div>
        <div class="input-group">
            <label for="title">Property title :</label>
            <input type="text" name="property_title" id="title"
                @if (isset($editProperty->title)) value="{{ $editProperty->title }}" @endif required>
        </div>
        <div class="input-group">
            <label for="price">Price :</label>
            <input type="number" name="property_price" id="price"
                @if (isset($editProperty->price)) value="{{ $editProperty->price }}" @endif required>
        </div>
        <div class="input-group">
            <label for="surface_int">Surface (INT) :</label>
            <input type="number" name="property_surface_int" id="surface_int"
                @if (isset($editProperty->int_surface)) value="{{ $editProperty->int_surface }}" @endif required>
        </div>
        <div class="input-group">
            <label for="surface_ext">Surface (EXT) :</label>
            <input type="number" name="property_surface_ext" id="surface_ext"
                @if (isset($editProperty->ext_surface)) value="{{ $editProperty->ext_surface }}" @endif required>
        </div>
        <div class="input-group">
            <label for="rooms">Rooms :</label>
            <input type="number" name="property_rooms" id="rooms"
                @if (isset($editProperty->rooms)) value="{{ $editProperty->rooms }}" @endif required>
        </div>
        <div class="textarea-group">
            <label for="description">Description :</label>
            <textarea name="property_description" id="description">
            @if (isset($editProperty->description))
{{ $editProperty->description }}
@endif
        </textarea>
        </div>
        <input type="hidden" name="property_id" value="{{ $editProperty->property_id }}">
        <input type="hidden" name="agency_id" value="{{ $editProperty->agency_id }}">
        @if (!empty($agent_id))
            <input type="hidden" name="agent_id" value="{{ $agent_id }}">
        @endif
        <div class="button-property-edit">
            @if (
                (isAuthorized(null, ['manager']) && $_SESSION['agency_id'] === (int) $editProperty->agency_id) ||
                    isset($editProperty->agent_id))
                <a href="/properties/delete/{{ $editProperty->property_id }}"
                    title="Delete property {{ $editProperty->reference }} {{ $editProperty->title }}">Delete
                    property</a>
            @endif
            <input type="submit" value="Update property">
        </div>

    </form>
</div>
