<div class="form-container">
    <h1>{{ $agency->name }}</h1>
    <div class="class-form">
        <form action="/agencies/update/{{ $agency->agency_id }}" method="post" id="connection-form"
            enctype="multipart/form-data">
            <div class="split-title">
                <h2>Edit your agency</h2>
            </div>
            <div class="input-group">
                <label for="agency_name">Name :</label>
                <input type="text" name="agency_name" id="agency_name" value="{{ $agency->name }}" required>
            </div>
            <div class="input-group">
                <label for="agency_address">Address :</label>
                <input type="text" name="agency_address" id="agency_address" value="{{ $agency->address }}" required>
            </div>
            <div class="input-group">
                <label for="agency_city">City :</label>
                <input type="text" name="agency_city" id="agency_city" value="{{ $agency->city }}" required>
            </div>
            <div class="input-group">
                <label for="agency_zipcode">Zipcode :</label>
                <input type="text" name="agency_zipcode" id="agency_zipcode" value="{{ $agency->zip }}" required>
            </div>
            <div class="input-group">
                <label for="agency_email">Email :</label>
                <input type="text" name="agency_email" id="agency_email" value="{{ $agency->email }}" required>
            </div>
            <div class="input-group">
                <label for="agency_phone">Phone number :</label>
                <input type="tel" name="agency_phone" id="agency_phone" value="{{ $agency->phone }}" required>
            </div>
            <div class="input-group">
                <label for="agency_image">
                    <img width="100" height="100"
                        src="/public/assets/images/agencies/agency_image_{{ $agency->agency_id }}.webp"
                        id="agency_image_preview"></label>
                <input type="file" name="agency_image" id="agency_image" accept="image/*">
            </div>
            <input type="hidden" name="agency_id" value="{{ $agency->agency_id }}">
            <input type="submit" value="Edit agency">
        </form>
    </div>
</div>
