<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seo['title'] ?? 'Real Estate RH' }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <link rel="icon" type="image/x-icon"
        href="{{ 'http://' . $_SERVER['HTTP_HOST'] . '/' }}public/assets/images/favicon.png">
    @if (isAuthorized(null, ['admin', 'manager', 'agent']))
        <link rel="stylesheet" href="{{ 'http://' . $_SERVER['HTTP_HOST'] . '/' }}public/css/admin_var.css">
    @else
        <link rel="stylesheet" href="{{ 'http://' . $_SERVER['HTTP_HOST'] . '/' }}public/css/var.css">
    @endif
    <link rel="stylesheet" href="{{ 'http://' . $_SERVER['HTTP_HOST'] . '/' }}public/css/normalize.css">
    <link rel="stylesheet" href="{{ 'http://' . $_SERVER['HTTP_HOST'] . '/' }}public/css/main.css">
    <link rel="stylesheet" href="{{ 'http://' . $_SERVER['HTTP_HOST'] . '/' }}public/css/form.css">
    <link rel="stylesheet" href="{{ 'http://' . $_SERVER['HTTP_HOST'] . '/' }}public/css/user_card.css">
    <link rel="stylesheet" href="{{ 'http://' . $_SERVER['HTTP_HOST'] . '/' }}public/css/property_card.css">
    <link rel="stylesheet" href="{{ 'http://' . $_SERVER['HTTP_HOST'] . '/' }}public/css/property_detail.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Patua+One&family=Playfair+Display&display=swap"
        rel="stylesheet">
</head>

<body>
    {{-- <div class="container"> --}}
    <header>
        @component('../../components.nav', ['pagename' => explode('/', $_SERVER['REQUEST_URI'])[1]])
        @endcomponent
    </header>

    <main>
        @if (isset($_SESSION['flash_message']) && !empty($_SESSION['flash_message']))
            <div class="flash-message">
                <h3 class="{{ $_SESSION['flash_message']['type'] }}">{{ $_SESSION['flash_message']['message'] }}
                </h3>
            </div>
        @endif
        @yield('content')
    </main>
    @component('components.footer')
    @endcomponent
    {{-- </div> --}}
    <script src="https://kit.fontawesome.com/82b9f37ffc.js" crossorigin="anonymous"></script>
    <script src="{{ 'http://' . $_SERVER['HTTP_HOST'] . '/' }}public/js/app.js"></script>
    <script src="{{ 'http://' . $_SERVER['HTTP_HOST'] . '/' }}public/js/form.js"></script>
    <script src="{{ 'http://' . $_SERVER['HTTP_HOST'] . '/' }}public/js/slider.js"></script>
</body>

</html>
