<?php

    use Illuminate\Support\Facades\Auth;
    $auth = Auth::user();

    if( !$auth || !$auth->admin){
        abort(403, 'Unauthorized action.');
    }

?>

@extends('layouts.app')

@section('title','Schedule')

@section('content')
    <div class="card mt-3 mb-3">
        <div class="card-header">
            Event
        </div>
        <div class="card-body">
            <form id='event-form' method="post" class='row p-2 needs-validation' novalidate>
                @csrf
                <div class='col-md-6 col-sm-12 mt-3'>
                    <label for="date">Date</label>
                    <input type="date" class="form-control" name="date" id="date" required>
                    <div class="invalid-feedback">
                        Valid date is required
                    </div>
                </div>
                <div class='col-md-6 col-sm-12 mt-3'>
                    <label for="time">Time</label>
                    <input type="time" class="form-control" name="time" id="time" required>
                    <div class="invalid-feedback">
                        Valid time is required
                    </div>
                </div>
                <div class='col-md-6 col-sm-12 mt-3'>
                    <label for="opponent-select">
                        Opponent
                        <button type="button" class="btn btn-sm btn-dark text-sm" data-toggle="modal" data-target="#opponentModal">New</button>
                    </label>
                    <select class="form-control" name="opponent" id="opponent-select" required>
                        <option value=''>-- SELECT --</option>
                        @if (count($opponents))
                            @foreach ($opponents as $opponent)
                                <option value='{{ $opponent->opponent }}'>{{ $opponent->opponent }}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="invalid-feedback">
                        Please select or add the opponent the game is against
                    </div>
                </div>
                <div class='col-md-6 col-sm-12 mt-3'>
                    <label for="location-select">
                        Location
                        <button type="button" class="btn btn-sm btn-dark text-sm invisible" data-toggle="modal" data-target="#locationModal">New</button>
                    </label>
                    <select class="form-control" name="location" id="location-select" required>
                        <option value=''>-- SELECT --</option>
                        @if (count($locations))
                            @foreach ($locations as $location)
                                <option value='{{ $location->id }}'>{{ $location->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="invalid-feedback">
                        Please select or add the location the game is at
                    </div>
                </div>
                <div class='col-md-6 col-sm-12 mt-3'>
                    <label for="season">Season</label>
                    <select class="form-control" name="season" id="season" required>
                        <option value=''>-- SELECT --</option>
                        <option value='SPR19'>Spring 2019</option>
                    </select>
                    <div class="invalid-feedback">
                        Please select or add the season the game is in
                    </div>
                </div>
                <div class='col-md-6 col-sm-12 mt-3'>
                    <label for="type">Type</label>
                    <select class="form-control" name="type" id="type" required>
                        <option value='R'>Regular Season</option>
                        <option value='P'>Playoffs</option>
                        <option value='F'>Finals</option>
                    </select>
                    <div class="invalid-feedback">
                        Please select the type of game it is
                    </div>
                </div>
                <div class='col-6 mt-3'>
                    <label for="our-score">Our Score</label>
                    <input type="number" class="form-control" min='0' name="our_score" id="our-score" disabled>
                    <div class="invalid-feedback">
                        Enter a valid score
                    </div>
                </div>
                <div class='col-6 mt-3'>
                    <label for="opponent-score">Opponent Score</label>
                    <input type="number" class="form-control" min='0' name="opponent_score" id="opponent-score" disabled>
                    <div class="invalid-feedback">
                        Enter a valid score
                    </div>
                </div>
                <div class='col-12 mt-5 text-center'>
                    {{-- <button type="button" class="btn btn-danger mr-5">Back</button> --}}
                    <button class='btn btn-primary' type='submit'>
                        @if ($event)
                            Update Event
                        @else
                            Create Event
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for new opponent-->
    <div class="modal fade" id="opponentModal" tabindex="-1" role="dialog" aria-labelledby="opponentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="opponentModalLabel">Add Opponent</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id='new-opponent-form' class='row p-3 needs-validation' novalidate>
                        <div class='col-12'>
                            <label for="opponentName">Opponent Name</label>
                            <input type="text" class="form-control" name="newOpponent" id="opponentName" required>
                            <div class="invalid-feedback">
                                Enter a valid opponent name
                            </div>
                            <!-- TODO: This is for server side, there is another version for browser defaults -->
                        </div>
                        <div class='col-12 text-center mt-5'>
                            <button id='submit-opponent' type="button" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        

        $("[name=date]").change(function(){
            let greaterDate = moment($(this).val()) > moment();
            
            $("[name=our_score],[name=opponent_score]").prop({
                disabled: greaterDate,
                required: !greaterDate
            })
            .val( greaterDate ? '' : 0);
            
        });

        $("#submit-opponent").click(() => {
            let newOpponent = $("[name=newOpponent]").val();

            $("[name=opponent]")
                .append(`<option value='${ newOpponent }'>${ newOpponent }</option>`)
                .val(newOpponent);

            $("#opponentModal .close").click();
        });

        @if ($event)
            $("[name=date]").val("{{ $event->date }}").change();
            $("[name=time]").val("{{ $event->start_time }}");
            $("[name=opponent]").val("{{ $event->opponent }}");
            $("[name=location]").val("{{ $event->location }}");
            $("[name=season]").val("{{ $event->season }}");
            $("[name=type]").val("{{ $event->type }}");
            $("[name=our_score]").val("{{ $event->our_score }}");
            $("[name=opponent_score]").val("{{ $event->opponent_score }}");
        @endif

        (function() {
            'use strict';
            window.addEventListener('load', function() {
                let eventForm = $("#event-form")[0];
                let opponentForm = $("#new-opponent-form")[0];

                eventForm.addEventListener('submit', function(event) {
                    
                    if (eventForm.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        event.preventDefault();
                        $.ajax({
                            url: '/schedule/{{ $event ? "update/$event->id" : 'create' }}',
                            type: "{{ $event ? 'PUT' : 'POST' }}",
                            data: $(eventForm).serialize(),
                            dataType: 'json',
                            success: json => {
                                if( json.Status ){
                                    window.location.assign('/schedule');
                                } else {
                                    toastr.error('An error has occured. Form was not submitted');
        
                                }
                            }
                        })

                    }

                    eventForm.classList.add('was-validated');

                }, false);
            }, false);
        })();
    </script>

@endsection
