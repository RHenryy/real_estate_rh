<article class="card-container">
    <div class="user-image">
        <img src="{{ $user->image }}" alt="Avatar of {{ $user->fname }} {{ $user->lname }}">
    </div>
    <div class="card-form">
        <form action="users/update/{{ $user->user_id }}" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="fname_{{ $user->user_id }}">Firstname :</label>
                <input type="text" name="update_fname" id="fname_{{ $user->user_id }}" value="{{ $user->fname }}"
                    required>
            </div>
            <div class="input-group">
                <label for="lname_{{ $user->user_id }}">Lastname :</label>
                <input type="text" name="update_lname" id="lname_{{ $user->user_id }}" value="{{ $user->lname }}"
                    required>
            </div>
            <div class="input-group">
                <label for="email_{{ $user->user_id }}">Email :</label>
                <input type="email" name="update_email" id="email_{{ $user->user_id }}" value="{{ $user->email }}"
                    required>
            </div>
            <div class="input-group">
                <label for="update_image">Avatar :</label>
                <input type="file" accept="image/*" name="update_image" id="update_image">
            </div>
            @if (!empty($manager) && (int) $manager->has_pending_application === 1)
                <div class="application-div">
                    <p>Application status : <span class="danger">Pending</span></p>
                </div>
            @elseif(!empty($manager) && (int) $manager->has_pending_application === 0)
                <div class="application-div">
                    <p>Application status : <span class="success">Approved</span></p>
                </div>
            @endif
            <div class="buttons-dashboard">
                @if (isAuthorized(null, ['manager']) && (int) $manager->has_pending_application === 0)
                    <a href="applications/cancel/{{ $_SESSION['agency_id'] }}" title="Cancel your subscription">Cancel
                        subscription
                    </a>
                @endif
                <input type="submit" value="Update">
            </div>
        </form>
    </div>
</article>
