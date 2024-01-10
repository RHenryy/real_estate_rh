<div class="form-container">
    <div class="form-title">
        <h1>Register an agent</h1>
    </div>
    <div class="class-form">
        <form action="/users/storeagent" method="post" id="connection-form">
            <div class="input-group">
                <label for="fname">Firstname :</label>
                <input type="text" name="fname" id="fname"
                    value="@if (isset($_SESSION['agent_fname'])) {{ $_SESSION['agent_fname'] }} @endif" required>
            </div>
            <div class="input-group">
                <label for="lname">Lastname :</label>
                <input type="text" name="lname" id="lname"
                    value="@if (isset($_SESSION['agent_lname'])) {{ $_SESSION['agent_lname'] }} @endif" required>
            </div>
            <div class="input-group">
                <label for="email">Email :</label>
                <input type="email" name="email" id="email"
                    value="@if (isset($_SESSION['agent_email'])) {{ $_SESSION['agent_email'] }} @endif" required>
            </div>

            <div class="input-group">
                <label for="password">Password :</label>
                <i class="fa-solid fa-eye show-pass"></i>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm password :</label>
                <i class="fa-solid fa-eye show-pass"></i>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <div class="input-group">
                <label for="type">Property :</label>
                <select name="agent_property">
                    <option value="">Assign agent</option>
                    @foreach ($properties as $property)
                        <option value="{{ $property->property_id }}">{{ $property->title }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="agency_id" value="{{ $manager->agency_id }}">
            <input type="hidden" name="manager_id" value="{{ $manager->manager_id }}">
            <input type="text" class="d-none" name="confirm_email">
            <input type="submit" value="Register">
        </form>
    </div>
</div>
