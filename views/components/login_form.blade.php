<div class="form-container">
    <div class="form-title">
        <h1>Login</h1>
    </div>
    <div class="class-form">
        <form action="/users/login" method="post" id="connection-form">
            <div class="input-group">
                <label for="email">Email :</label>
                <input type="email" name="login_email" id="email"
                    value="@if (isset($_SESSION['email'])) {{ $_SESSION['email'] }} @endif" required>
            </div>
            <div class="input-group">
                <label for="password">Password :</label>
                <i class="fa-solid fa-eye show-pass"></i>
                <input type="password" name="login_password" id="password" required>
            </div>
            <input type="text" class="d-none" name="confirm_email">
            <input type="submit" value="Login">
        </form>
    </div>
</div>
