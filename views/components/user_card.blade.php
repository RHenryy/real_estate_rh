@foreach ($users as $user)
    <div class="card-container">
        <div class="user-image">
            <img src="{{ $user->image }}" alt="Avatar of {{ $user->fname }} {{ $user->lname }}">
        </div>
        <div class="card-form">
            <form action="users/update/{{ $user->user_id }}" method="post" enctype="multipart/form">
                <div class="input-group">
                    <label for="fname_{{ $user->user_id }}">Firstname :</label>
                    <input type="text" name="update_fname" id="fname_{{ $user->user_id }}"
                        value="{{ $user->fname }}" required>
                </div>
                <div class="input-group">
                    <label for="lname_{{ $user->user_id }}">Lastname :</label>
                    <input type="text" name="update_lname" id="lname_{{ $user->user_id }}"
                        value="{{ $user->lname }}" required>
                </div>
                <div class="input-group">
                    <label for="email_{{ $user->user_id }}">Email :</label>
                    <input type="email" name="update_email" id="email_{{ $user->user_id }}"
                        value="{{ $user->email }}" required>
                </div>
                @if (isset($user->agency->name) && !empty($user->agency->name))
                    <div class="input-group agency">
                        <p class="ml-0">Agency :</p>
                        <p class="ml-0">
                            <a href="agencies/detail/{{ $user->agency_id }}"
                                title="{{ $user->agency->name }} detail page">{{ $user->agency->name }}</a>
                        </p>
                    </div>
                @endif
                <div class="card-submit">
                    <input type="submit" value="Update User">
                </div>
            </form>
            <a href="users/delete/{{ $user->user_id }}"><i class="fa-solid fa-circle-xmark fa-2x"></i></a>
        </div>
    </div>
@endforeach
