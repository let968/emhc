<?php

    use Illuminate\Support\Facades\Auth;
    $auth = Auth::user();

?>
@extends('layouts.app')

@section('title','Roster')

@section('content')
    

    <div class='row'>
        @if ($auth && $auth->admin)
            <div class='col-12 mt-5'>
                <a class="btn btn-primary" href="/roster/add" role="button">Add Player</a>
            </div>
        @endif
        <div class='col-12 mt-5 mb-3'>
            <table class='table table-striped table-bordered text-center'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        @if ($auth && $auth->admin)
                            <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (count($players))
                        @foreach ($players as $player)
                            <tr>
                                <td class='align-middle'>{{ $player->number }}</td>
                                <td class='align-middle'>{{ $player->name }}</td>
                                <td class='align-middle'>
                                    <i 
                                        class='fas
                                            @switch($player->status)
                                                @case('A')
                                                    fa-check text-success 
                                                    @break
                                                @case('I')
                                                    fa-user-injured text-danger
                                                    @break
                                                @case('S')
                                                    fa-bone text-dark
                                                @default
                                                    
                                            @endswitch
                                        '
                                    style='font-size:25px'></i>
                                </td>
                                @if ($auth && $auth->admin)
                                    <td class='align-middle'>
                                        <a class="btn btn-sm btn-secondary ml-2 mr-2 mt-1 mb-1" href="/roster/add/{{ $player->id }}" role="button">
                                            <i class='fas fa-pen'></i>
                                        </a>
                                        <a class="btn btn-sm btn-danger ml-2 mr-2 mt-1 mb-1 delete" href="javascript:void()" playerId="{{ $player->id }}" role="button">
                                            <i class='fas fa-trash'></i>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan='4'>
                                <div class='alert alert-warning m-0'>No players on the roster</div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(".delete").click(function(){
            if( confirm('Are you sure you want to delete this player?') ){
                

                $.ajax({
                    url: '/roster/delete/' + $(this).attr('playerId'),
                    type: 'delete',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: json => {
                        if(json.Status){
                            $(this).closest('tr').remove();
                            toastr.success('Player removed');
                        } else {
                            toastr.error('An error has occured');
                        }
                    }
                })
            }
        });
    </script>

@endsection