@foreach ($users as $user)
    <div class="card-container application">
        <div class="user-image">
            <img src="{{ $user->image }}" alt="Avatar of {{ $user->fname }} {{ $user->lname }}">
        </div>
        <div class="card-form">
            <form action="users/update/{{ $user->user_id }}" method="post" enctype="multipart/form">
                <div class="input-group">
                    <p>Firstname :</p>
                    <p>{{ $user->fname }}</p>
                </div>
                <div class="input-group">
                    <p>Lastname :</p>
                    <p>{{ $user->lname }}</p>
                </div>
                <div class="input-group">
                    <p>Email :</p>
                    <p>{{ $user->email }}</p>
                </div>
                <div class="input-group">
                    <p>Agency :</p>
                    <p><a href="agencies/detail/{{ $user->agency->agency_id }}"
                            title="Show detail agency page">{{ $user->agency->name }}</a></p>
                </div>
                <div class="application-submit">
                    <a href="applications/accept/{{ $user->manager_id }}">Accept</a>
                    <a href="applications/reject/{{ $user->manager_id }}">Deny</a>
                </div>
            </form>
        </div>
    </div>
@endforeach
