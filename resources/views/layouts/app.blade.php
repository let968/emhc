@php
    use Illuminate\Support\Facades\Auth;
    use App\Stats;

    $auth = Auth::id();

    $stat = new Stats;
    $stats = $stat->pointLeaders();

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
        <nav class="navbar navbar-expand-lg navbar-dark bg-team-primary position-fixed w-100" style='z-index: 2'>
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

    <div class='row pl-2 pr-2'>
        <div class='col-md-2 d-none d-sm-block position-relative mt-5' style='top: 55px'>
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-light">
                    Record
                </div>
                <div class="card-body p-2 font-weight-bold text-center">
                    {{ $stats->record->wins }} - {{ $stats->record->losses }}
                </div>
            </div>
            <div class="card shadow-sm mt-2">
                <div class="card-header bg-dark text-light">
                    Point Leaders
                </div>
                <div class="card-body p-0">
                    <table class='table table-striped text-center m-0'>
                        <thead>
                            <tr>
                                <th class='p-1'>Name</th>
                                <th class='p-1'>G</th>
                                <th class='p-1'>A</th>
                                <th class='p-1'>P</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stats->leaders as $leader)
                                <tr>
                                    <td class='align-middle text-left'>{{ $leader->name }}</td>
                                    <td class='align-middle'>{{ $leader->goals }}</td>
                                    <td class='align-middle'>{{ $leader->assists }}</td>
                                    <td class='align-middle'>{{ $leader->points }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id='app' class='col-md-10 col-12 overflow-auto position-relative' style='top: 55px'>
            @yield('content')
        </div>
    </div>
</body>
</html>