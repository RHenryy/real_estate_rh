<div class="form-container">
    <h1>Submit your application to join our network today !</h1>
    <div class="class-form">
        <form action="/partners/register" method="post" id="connection-form" enctype="multipart/form-data">
            <div class="split-title">
                <h2>Register your agency</h2>
            </div>
            <div class="input-group">
                <label for="agency_name">Name :</label>
                <input type="text" name="agency_name" id="agency_name"
                    value="@if (isset($_SESSION['agency_name'])) {{ $_SESSION['agency_name'] }} @endif" required>
            </div>
            <div class="input-group">
                <label for="agency_address">Address :</label>
                <input type="text" name="agency_address" id="agency_address"
                    value="@if (isset($_SESSION['agency_address'])) {{ $_SESSION['agency_address'] }} @endif" required>
            </div>
            <div class="input-group">
                <label for="agency_city">City :</label>
                <input type="text" name="agency_city" id="agency_city"
                    value="@if (isset($_SESSION['agency_city'])) {{ $_SESSION['agency_city'] }} @endif" required>
            </div>
            <div class="input-group">
                <label for="agency_zipcode">Zipcode :</label>
                <input type="text" name="agency_zipcode" id="agency_zipcode"
                    value="@if (isset($_SESSION['agency_zipcode'])) {{ $_SESSION['agency_zipcode'] }} @endif" required>
            </div>
            <div class="input-group">
                <label for="agency_email">Email :</label>
                <input type="text" name="agency_email" id="agency_email"
                    value="@if (isset($_SESSION['agency_email'])) {{ $_SESSION['agency_email'] }} @endif" required>
            </div>
            <div class="input-group">
                <label for="agency_phone">Phone number :</label>
                <input type="tel" name="agency_phone" id="agency_phone"
                    value="@if (isset($_SESSION['agency_phone'])) {{ $_SESSION['agency_phone'] }} @endif" required>
            </div>
            <div class="input-group">
                <label for="agency_image">Photo :</label>
                <input type="file" name="agency_image" id="agency_image" accept="image/*" required>
            </div>
            <input type="text" class="d-none" name="confirm_email">
            <input type="submit" value="Apply">
        </form>
    </div>
</div>
