<div class="form-title">
    <h2>Add a property to be displayed on our website</h2>
    <i aria-label="Close property form window" class="fa-solid fa-circle-xmark fa-2x close-modal"></i>
</div>
<div class="class-form property">
    <form action="/properties/store" method="post" id="connection-form" enctype="multipart/form-data">
        <div class="input-group">
            <label for="reference">Reference :</label>
            <input type="text" name="property_reference" id="reference"
                value="@if (isset($_SESSION['reference'])) {{ $_SESSION['reference'] }} @endif" required>
        </div>
        <div class="input-group">
            <label for="address">Complete Address :</label>
            <input type="text" name="property_address" id="address"
                value="@if (isset($_SESSION['address'])) {{ $_SESSION['address'] }} @endif" required>
            <div class="custom-select d-none">
                <ul></ul>
            </div>
        </div>
        <div class="input-group">
            <label for="street">Street :</label>
            <input type="text" name="property_street" id="street" required>
        </div>
        <div class="input-group">
            <label for="city">City :</label>
            <input type="text" name="property_city" id="city" required>
        </div>
        <div class="input-group">
            <label for="zipcode">Zipcode :</label>
            <input type="text" name="property_zipcode" id="zipcode" required>
        </div>
        <div class="input-group">
            <label for="offer">Offer :</label>
            <select name="property_offer" id="">
                <option value=""></option>
                <option value="sale">For sale</option>
                <option value="rent">For rent</option>
            </select>
        </div>
        <div class="input-group">
            <label for="type">Property type :</label>
            <select name="property_type" id="">
                <option value=""></option>
                <option value="house">House</option>
                <option value="appartment">Appartment</option>
                <option value="studio">Studio flat</option>
                <option value="land">Land</option>
            </select>
        </div>
        <div class="input-group">
            <label for="title">Property title :</label>
            <input type="text" name="property_title" id="title" required>
        </div>
        <div class="input-group">
            <label for="price">Price :</label>
            <input type="number" name="property_price" id="price" required>
        </div>
        <div class="input-group">
            <label for="surface_int">Surface (INT) :</label>
            <input type="number" name="property_surface_int" id="surface_int" required>
        </div>
        <div class="input-group">
            <label for="surface_ext">Surface (EXT) :</label>
            <input type="number" name="property_surface_ext" id="surface_ext" required>
        </div>
        <div class="input-group">
            <label for="rooms">Rooms :</label>
            <input type="number" name="property_rooms" id="rooms" required>
        </div>
        <div class="input-group">
            <label for="property_image">Images :</label>
            <input type="file" accept="image/*" name="property_image[]" id="property_image" multiple>
        </div>
        <div class="show-images">

        </div>
        <div class="textarea-group">
            <label for="description">Description :</label>
            <textarea name="property_description" id="description"></textarea>
        </div>
        <input type="hidden" name="agency_id" value="{{ $agency_id }}">
        @if (!empty($agent_id))
            <input type="hidden" name="agent_id" value="{{ $agent_id }}">
        @endif
        <input type="text" class="d-none" name="confirm-property">
        <input type="submit" value="Add property">
    </form>
</div>
