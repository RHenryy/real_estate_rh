@extends('layouts.layout')
@section('content')
    <section class="user-cards-section">
        @if (isAuthorized())
            <h1>Manage users</h1>
            <div class="flex-users-choice">
                <p class="show-managers active-filter">Managers</p>
                <p class="show-agents">Agents</p>
                <p class="show-users">Users</p>
            </div>
            @if (!empty($managers))
                <article id="show-managers" class="">
                    <h2>Managers</h2>
                    <div class="cards-wrapper">
                        @component('components.user_card', ['users' => $managers])
                        @endcomponent
                    </div>
                </article>
            @endif
            @if (!empty($agents))
                <article id="show-agents" class="d-none">
                    <h2>Agents</h2>
                    <div class="cards-wrapper">
                        @component('components.user_card', ['users' => $agents])
                        @endcomponent
                    </div>
                </article>
            @endif
            @if (!empty($users))
                <article id="show-users" class="d-none">
                    <h2>Users</h2>
                    <div class="cards-wrapper">
                        @component('components.user_card', ['users' => $users])
                        @endcomponent
                    </div>
                </article>
            @endif
        @endif
        @if (isAuthorized(null, ['manager']))
            <h1>Manage agents</h1>
            <div class="wrapper agent">
                <h3 class="show-property-form" aria-label="Open agent form window"><span>Create an agent account</span></h3>
                <div class="property-form-backdrop d-none">
                    <div class="property-form-modal">
                        @component('components.agent_form')
                        @endcomponent
                    </div>
                </div>
                @if (!empty($agents))
                    @component('components.agent_card')
                    @endcomponent
                @endif
            </div>
        @endif
    </section>
@endsection
