@php
    use Illuminate\Support\Facades\Auth;

    $auth = Auth::id();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/images/evil_monkey.svg" />

    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/app.js"></script>
    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
    <title>EMHC - @yield('title')</title>
</head>
<body class='overflow-hidden'>
    @section('header')
        <nav class="navbar navbar-expand-lg navbar-dark bg-team-primary position-relative" style='z-index: 2'>
            <a class="navbar-brand" href="/">
                <img src="/images/evil_monkey.svg" width='30' height="30" class='d-inline-block align-top'>
                EMHC
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/roster">Roster</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/schedule">Schedule</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    @if ($auth)
                        <a class='nav-link' href='/logout'>Sign Out</a>
                    @else
                        <a class='nav-link' href='/login'>Sign In</a>
                    @endif
                    </ul>
            </div>
        </nav>
    @show

    <div id='app' class='container overflow-auto position-absolute' style='top: 55px;bottom: 0;left: 0;right: 0;'>
        @yield('content')
    </div>
</body>
</html>