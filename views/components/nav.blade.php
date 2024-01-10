<!-- DESKTOP NAV -->
<nav id="desktop-nav">
    <div class="main-logo">
        <a href="/" title="Home"><img src="/public/assets/images/logo-real-estate.webp" alt="Logo real estate"></a>
        <p>Your real estate network</p>
    </div>
    <div class="nav-list">
        <ul>
            @php
            @endphp
            @if (!isAuthorized(null, ['manager', 'agent']))
                <li><a href="/agencies" title="Agencies"
                        class="@if ($pagename === 'agencies') active @endif">Agencies</a>
                </li>
                <li><a href="/properties" title="Properties"
                        class="@if ($pagename === 'properties') active @endif">Properties</a></li>
            @else
                <li><a href="/myagency" title="My agency" class="@if ($pagename === 'myagency' || $pagename === 'agencies') active @endif">My
                        Agency</a>
                </li>
                <li><a href="/myproperties" title="My properties"
                        class="@if ($pagename === 'myproperties' || $pagename === 'properties') active @endif">My Properties</a></li>
            @endif
            @if (empty($_SESSION['user']))
                <li><a href="/register" title="Register"
                        class="@if ($pagename === 'register') active @endif">Register</a></li>
                <li><a href="/login" title="Login" class="@if ($pagename === 'login') active @endif">Login</a>
                </li>
            @else
                @if (isAuthorized(null, ['manager']))
                    <li><a href="/users" title="Manage your agents"
                            class="@if ($pagename === 'users') active @endif">Manage agents</a></li>
                @endif
                @if (isAuthorized(null, ['admin']))
                    <li><a href="/users" title="Manage users"
                            class="@if ($pagename === 'users') active @endif">Manage users</a></li>
                    <li><a href="/applications" title="Pending manager applications"
                            class="@if ($pagename === 'applications') active @endif">Pending applications
                            @if (isset($_SESSION['pending_applications']))
                                <i class="fa-solid fa-circle notification"></i>
                            @endif
                        </a></li>
                @endif
                <li><a href="/dashboard" title="Dashboard"
                        class="@if ($pagename === 'dashboard') active @endif">Dashboard</a></li>
                <li><a href="/disconnect" title="Disconnect"
                        class="@if ($pagename === 'disconnect') active @endif">Disconnect</a></li>
            @endif
            @if (!isAuthorized(null, ['admin', 'manager', 'agent']))
                <li><a href="/partners" title="Become a partner"
                        class="@if ($pagename === 'partners') active @endif">Become a partner</a></li>
            @endif
        </ul>
    </div>
</nav>
<!-- MOBILE NAV -->
<div id="mobile-nav">
    <div class="main-logo">
        <a href="/"><img src="/public/assets/images/logo-real-estate.webp" alt="Logo real estate"></a>
        <p class="mobile-slogan">Your real estate network</p>
    </div>
    <div class="nav-list" id="burger-menu">
        <ul>
            <li><i class="fa-solid fa-bars" id="burger-trigger"></i></li>
        </ul>
    </div>
</div>
<nav class="modal-nav d-none">
    <div class="nav-container">
        <a href="/"><img src="/public/assets/images/logo-real-estate.webp" alt="Logo real estate"></a>
        <p>Your real estate network</p>
        <ul>
            @if (!isAuthorized(null, ['manager', 'agent']))
                <li><a href="/agencies" title="Agencies"
                        class="@if ($pagename === 'agencies') active @endif">Agencies</a>
                </li>
                <li><a href="/properties" title="Properties"
                        class="@if ($pagename === 'properties') active @endif">Properties</a></li>
            @else
                <li><a href="/myagency" title="My agency" class="@if ($pagename === 'myagency' || $pagename === 'agencies') active @endif">My
                        Agency</a>
                </li>
                <li><a href="/myproperties" title="My properties"
                        class="@if ($pagename === 'myproperties' || $pagename === 'properties') active @endif">My Properties</a></li>
            @endif
            @if (empty($_SESSION['user']))
                <li><a href="/register" title="Register"
                        class="@if ($pagename === 'register') active @endif">Register</a></li>
                <li><a href="/login" title="Login" class="@if ($pagename === 'login') active @endif">Login</a>
                </li>
            @else
                @if (isAuthorized(null, ['manager']))
                    <li><a href="/users" title="Manage your agents"
                            class="@if ($pagename === 'users') active @endif">Manage agents</a></li>
                @endif
                @if (isAuthorized(null, ['admin']))
                    <li><a href="/users" title="Manage users"
                            class="@if ($pagename === 'users') active @endif">Manage users</a></li>
                    <li><a href="/applications" title="Pending manager applications"
                            class="@if ($pagename === 'applications') active @endif">Pending applications</a></li>
                @endif
                <li><a href="/dashboard" title="Dashboard"
                        class="@if ($pagename === 'dashboard') active @endif">Dashboard</a></li>
                <li><a href="/disconnect" title="Disconnect"
                        class="@if ($pagename === 'disconnect') active @endif">Disconnect</a></li>
            @endif
            @if (!isAuthorized(null, ['admin', 'manager', 'agent']))
                <li><a href="/partners" title="Become a partner"
                        class="@if ($pagename === 'partners') active @endif">Become a partner</a></li>
            @endif
        </ul>
    </div>
    <div class="close-modal-nav"><i class="fa-solid fa-xmark fa-2x"></i></div>
</nav>
