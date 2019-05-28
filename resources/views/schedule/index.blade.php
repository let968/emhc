<?php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use App\Season;

    $auth = Auth::user();

    $s = new Season;

    if( !$season ){
        $season = $s->getCurrentSeason();
        $season = empty($season) ? $season->id : '';
    }

    $all_seasons = DB::table('event')
                ->leftJoin('season','season.id','=','event.season')
                ->select('event.season','season.name')
                ->distinct()
                ->orderBy('season.name','asc')
                ->get();
    
?>
@extends('layouts.app')

@section('title','Schedule')

@section('content')

    <style>
        .table-danger{
            background-color: #f7c6c5 !important;
        }

        .table-success{
            background-color: #c7eed8 !important;
        }
    </style>

    @if ($auth && $auth->admin)
        <div class='row border-bottom'>
            <div class='mt-4 col-12'>
                <a name="" id="" class="btn btn-primary" href="/schedule/create" role="button">Add Event</a>
            </div>
            <div class='mt-4 col-lg-4'>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Season</span>
                    </div>
                    <select name="seasonFilter" id="" class="form-control" onchange="window.location.assign(`/schedule/${ this.value }`)">
                        <option value=""></option>
                        @foreach ($all_seasons as $s)
                            <option value="{{ $s->season }}"
                                {{ $s->season == $season ? 'selected' : '' }}
                                >{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif
    
    <div class='row mb-3'>
        @if (count($events))

            @foreach ($events as $event)
                @php
                    $color = $event->our_score > $event->opponent_score 
                                ? 'success'
                                : ($event->our_score === $event->opponent_score 
                                    ? ''
                                    : 'danger')
                @endphp
                
                <div class='col-12'>
                    <div class='card shadow-sm mt-3 table-{{ $color }}'>
                        <div class='card-header d-flex text-black-50 align-items-center'>
                            <div class='mr-4'>
                                <i class='fas fa-calendar mr-1'></i>    
                                <span>{{ date("m/d/Y", strtotime($event->date) ) }}</span>
                            </div>
                            <div>
                                <i class='fas fa-clock mr-1'></i>
                                <span>{{ date("g:i A", strtotime($event->start_time)) }}</span>
                            </div>
                            <div class='ml-auto'>
                                @if (strtotime(date("m/d/y H:i")) > strtotime($event->date . ' ' . $event->start_time))
                                    <a 
                                        class="btn btn-sm btn-primary"
                                        href="/stats/game/{{ $event->id }}" 
                                        role="button"
                                    >Stats</a>
                                @else
                                    <a 
                                        class="btn btn-sm btn-secondary"
                                        href="/lineup/{{ $event->id }}" 
                                        role="button"
                                    >Line-up</a>
                                @endif
                            </div>
                        </div>
                        <div class='body'>
                            <div class='d-flex p-3 {{ $event->our_score > $event->opponent_score ? 'font-weight-bold': '' }}'>
                                <div class=''>Evil Monkeys</div>
                                <div class='ml-auto'>{{ $event->our_score }}</div>
                            </div>
                            <div class='d-flex p-3 {{ $event->our_score < $event->opponent_score ? 'font-weight-bold': '' }} border-top'>
                                <div class=''>{{ $event->opponent }}</div>
                                <div class='ml-auto'>{{ $event->opponent_score }}</div>
                            </div>
                        </div>
                        <div class='card-footer d-flex align-items-center text-black-50 table-{{ $color }}'>
                            <div class='mr-4'>
                                <i class='fas fa-location-arrow mr-1'></i>
                                <span>{{ $event->name }}</span>
                            </div>
                            <div>
                                <i class='fas fa-leaf mr-1'></i>
                                <span>{{ $event->season_name }}</span>
                            </div>
                            <div class='ml-auto'>
                                @if ($auth && $auth->admin)
                                    <a 
                                        class="btn btn-sm btn-dark"
                                        href="/schedule/create/{{ $event->id }}" 
                                        role="button"
                                    >Edit</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
    
        @endif
    </div>

@endsection