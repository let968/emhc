@php

    use Illuminate\Support\Facades\Auth;

    $auth = Auth::user();

    $canEdit = 0;
    foreach ($stats as $key => $player) {
        if( $auth && $auth->roster_id === $player->player_id ){
            $canEdit = 1;
            break;
        }
    }
@endphp

@extends('layouts.app')

@section('title','Stats')

@section('content')
    

    <div class='row mt-4'>
        <div class='col-6 text-center'>
            <h3>Evil Monkeys</h3>
        </div>
        <div class='col-6 text-center'>
            <h3>{{ $event->opponent }}</h3>
        </div>
        <div class='col-6 text-center'>
            <div class='alert alert-{{ $event->our_score > $event->opponent_score ? 'success' : 'dark' }} d-inline-block'>{{ $event->our_score }}</div>
        </div>
        <div class='col-6 text-center'>
            <div class='alert alert-{{ $event->our_score < $event->opponent_score ? 'danger' : 'dark' }} d-inline-block'>{{ $event->opponent_score }}</div>
        </div>
    </div>
    <div class='row mt-4 mb-4'>
        <div class='col-12'>
            <div class='table-responsive'>
                <table class='table table-striped table-bordered text-center'>
                    <thead>
                        <tr class='bg-dark text-light'>
                            <th>#</th>
                            <th>Name</th>
                            <th>G</th>
                            <th>A</th>
                            <th>P</th>
                            <th>Blk</th>
                            <th>PIM</th>
                            @if ($auth && ($auth->admin || $canEdit))
                                <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = (object)[
                                'goals'     => 0,
                                'assists'   => 0,
                                'blocks'    => 0,
                                'pims'      => 0
                            ];
                        @endphp
                        @foreach ($stats as $player)
                            @php
                                $total->goals   += $player->goals ?: 0;
                                $total->assists += $player->assists ?: 0;
                                $total->blocks  += $player->blocks ?: 0;
                                $total->pims    += $player->pims ?: 0;
                            @endphp
                            <tr>
                                <td class='align-middle'>{{ $player->number }}</td>
                                <td class='align-middle'>{{ $player->name }}</td>
                                <td class='align-middle'>{{ $player->goals ?: 0 }}</td>
                                <td class='align-middle'>{{ $player->assists ?: 0 }}</td>
                                <td class='align-middle'>{{ $player->goals || $player->assists ? $player->goals + $player->assists : 0 }}</td>
                                <td class='align-middle'>{{ $player->blocks ?: 0 }}</td>
                                <td class='align-middle'>{{ $player->pims ?: 0 }}:00</td>
                                @if ($canEdit)
                                    <td class='align-middle'>
                                    @if($auth && ($auth->admin || $auth->roster_id === $player->player_id))
                                        <button class='btn btn-sm btn-primary' data-toggle="modal" data-target="#statsModal" onclick="editStats('{{ json_encode($player) }}')">
                                            <i class="fas fa-pen" aria-hidden="true"></i>
                                        </button>
                                    @endif
                                    </td>
                                    
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class='bg-dark text-light' colspan='2'></th>
                            <th class='bg-dark text-light'>{{ $total->goals }}</th>
                            <th class='bg-dark text-light'>{{ $total->assists }}</th>
                            <th class='bg-dark text-light'>{{ $total->goals + $total->assists }}</th>
                            <th class='bg-dark text-light'>{{ $total->blocks }}</th>
                            <th class='bg-dark text-light'>{{ $total->pims }}:00</th>
                            @if($auth && ($auth->admin || $canEdit))
                                <th class='bg-dark text-light'></th>
                            @endif
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for stats-->
    <div class="modal fade" id="statsModal" tabindex="-1" role="dialog" aria-labelledby="statsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statsModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id='new-stats-form' class='row p-3 needs-validation' novalidate>
                        @csrf
                        <div class='col-3'>
                            <label for="goals">Goals</label>
                            <input type="number" min='0' class="form-control" name="goals" id="goals" required>
                            <div class="invalid-feedback">
                                Enter a valid stats name
                            </div>
                            <!-- TODO: This is for server side, there is another version for browser defaults -->
                        </div>
                        <div class='col-3'>
                            <label for="assists">Assists</label>
                            <input type="number" min='0' class="form-control" name="assists" id="assists" required>
                            <div class="invalid-feedback">
                                Enter a valid stats name
                            </div>
                            <!-- TODO: This is for server side, there is another version for browser defaults -->
                        </div>
                        <div class='col-3'>
                            <label for="blocks">Blocks</label>
                            <input type="number" min='0' class="form-control" name="blocks" id="blocks" required>
                            <div class="invalid-feedback">
                                Enter a valid stats name
                            </div>
                            <!-- TODO: This is for server side, there is another version for browser defaults -->
                        </div>
                        <div class='col-3'>
                            <label for="pims">PIMs</label>
                            <input type="number" min='0' class="form-control" name="pims" id="pims" required>
                            <div class="invalid-feedback">
                                Enter a valid stats name
                            </div>
                            <!-- TODO: This is for server side, there is another version for browser defaults -->
                        </div>
                        <div class='col-12 text-center mt-5'>
                            <button id='submit-stats' class="btn btn-primary">Submit</button>
                        </div>
                        <input type="hidden" name="player_id" value=''>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editStats($player){
            let modal = $("#statsModal");

            $player = JSON.parse($player);
            
            $(".modal-title",modal).text($player.name);
            $("[name=goals]",modal).val( $player.goals ? $player.goals : 0);
            $("[name=assists]").val( $player.assists ? $player.assists : 0);
            $("[name=blocks]").val( $player.blocks ? $player.blocks : 0);
            $("[name=pims]").val( $player.pims ? $player.pims : 0);
            $("[name=player_id]").val( $player.player_id );

            $('body').append(modal);

            $('form',modal).submit(e=>{
                e.preventDefault();

                $.ajax({
                    url: '/stats/{{ $event->id }}',
                    type: 'PUT',
                    data: $('form',modal).serialize(),
                    dataType: 'json',
                    success: json => {
                        if( json.Status ){
                            window.location.reload();
                        } else {
                            toastr.error('An error has occured');
                        }
                    }
                })
            })
        }
    </script>
@endsection

