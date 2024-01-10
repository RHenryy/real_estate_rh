<div class="form-container">
    <div class="form-title">
        <h1>Register</h1>
    </div>
    <div class="class-form">
        <form action="/users/store" method="post" id="connection-form" class="register-form">
            <div class="input-group">
                <label for="fname">Firstname :</label>
                <input type="text" name="fname" id="fname"
                    value="@if (isset($_SESSION['fname'])) {{ $_SESSION['fname'] }} @endif" required>
            </div>
            <div class="input-group">
                <label for="lname">Lastname :</label>
                <input type="text" name="lname" id="lname"
                    value="@if (isset($_SESSION['lname'])) {{ $_SESSION['lname'] }} @endif" required>
            </div>
            <div class="input-group">
                <label for="email">Email :</label>
                <input type="email" name="register_email" id="email"
                    value="@if (isset($_SESSION['email'])) {{ $_SESSION['email'] }} @endif" required>
            </div>
            <div class="input-group">
                <label for="password">Password :</label>
                <i class="fa-solid fa-eye show-pass"></i>
                <input type="password" name="register_password" id="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm password :</label>
                <i class="fa-solid fa-eye show-pass"></i>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <input type="text" class="d-none" name="confirm_email">
            <input type="submit" value="Register">
        </form>
    </div>
</div>
