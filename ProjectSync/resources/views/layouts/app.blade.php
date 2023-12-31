<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="{{ asset('css/faq.css') }}" rel="stylesheet">
    <link href="{{ asset('css/about.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('css/recover.css') }}" rel="stylesheet">
    <link href="{{ asset('css/task.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/contacts.css') }}" rel="stylesheet">
    <link href="{{ asset('css/popup.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('js/app.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/faq.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/contacts.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/about.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/popup_confirms.js') }}" defer></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js" defer></script>
    <script type="text/javascript" src="{{ asset('js/pusher.js') }}" defer></script>
</head>
<body>
    <main>
        @include('partials.header')
        <div aria-live="polite" aria-atomic="true" style="position: fixed; bottom: 10px; right: 10px; z-index: 999999999;">
            <div id="toastContainer"></div>
        </div>
        <section id="content">
            @yield('content')
        </section>
    </main>
    @include('partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
