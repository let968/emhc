<?php
    use Illuminate\Support\Facades\Auth;
    $auth = Auth::user();
    
?>
@extends('layouts.app')

@section('title','Schedule')

@section('content')
    @if ($auth && $auth->admin)
        <div class='mt-4'>
            <a name="" id="" class="btn btn-primary" href="/schedule/create" role="button">Add Event</a>
        </div>
    @endif
    <style>
        .table-danger{
            background-color: #f7c6c5 !important;
        }

        .table-success{
            background-color: #c7eed8 !important;
        }
    </style>
    <div class='row mt-3 mb-3'>
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
                                        href="/stats/{{ $event->id }}" 
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
                        <div class='card-footer d-flex align-items-center table-{{ $color }}'>
                            <div class='d-flex align-items-center text-black-50'>
                                <i class='fas fa-location-arrow mr-2'></i>
                                <div class=''>{{ $event->name }}</div>
                            </div>
                            <div class='text-black-50 ml-auto'>
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