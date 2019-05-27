@php
    use Illuminate\Support\Facades\Auth;
    $auth = Auth::user();
@endphp

@extends('layouts.app')

@section('title','Line-up')

@section('content')
    <style>
        .btn-group-xs > .btn, .btn-xs {
            padding: .25rem .4rem;
            font-size: .875rem;
            line-height: .5;
            border-radius: .2rem;
        }
    </style>
    <div class='d-none d-md-block'>
        <div class='d-flex mt-3 align-items-center'>
            <div class='mr-4'>
                <i class='fas fa-users mr-1'></i>
                <span>{{ $event->opponent }}</span>
            </div>
        </div>
        <div class='d-flex mt-3 align-items-center'>
            <div class='mr-3'>
                <i class='fas fa-calendar mr-1'></i>    
                <span>{{ date("n/j/Y", strtotime($event->date) ) }}</span>
            </div>
            <div class='mr-3'>
                <i class='fas fa-clock mr-1'></i>
                <span>{{ date("g:i A", strtotime($event->start_time)) }}</span>
            </div>
            <div>
                <i class='fas fa-location-arrow mr-1'></i>
                <span class=''>{{ $event->name }}</span>
            </div>
        </div>
    </div>
    <div class='row mt-3 align-items-center d-sm-none'>
        <div class='col-6 mt-2'>
            <i class='fas fa-calendar mr-1'></i>    
            <span>{{ date("n/j/Y", strtotime($event->date) ) }}</span>
        </div>
        <div class='col-6 mt-2 text-right'>
            <i class='fas fa-users mr-1'></i>
            <span>{{ $event->opponent }}</span>
        </div>
        <div class='col-6 mt-2'>
            <i class='fas fa-clock mr-1'></i>
            <span>{{ date("g:i A", strtotime($event->start_time)) }}</span>
        </div>
        <div class='col-6 mt-2 text-right'>
            <i class='fas fa-location-arrow mr-1'></i>
            <span class=''>{{ $event->name }}</span>
        </div>
    </div>
    <div class='row'>
        <div class='col-xs-12 col-md-4 mt-3'>
            <table id='in' class='table table-striped table-bordered bg-white shadow-sm'>
                <thead>
                    <tr>
                        <th class='bg-success text-light'>Playing</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 0 ?>
                    @if (count($lineup))
                        @foreach ($lineup as $player)
                            @if ($player->status === 'I')
                                @php  $count++ @endphp
                                <tr>
                                    <td class='table-success d-flex align-items-center'>
                                        <div>{{ $player->name }}</div>
                                        @if ($auth && ( $auth->admin || $auth->roster_id === $player->player_id ))
                                            <button type="button" class="btn btn-sm btn-primary ml-auto" onclick="choice({{ $player->player_id }},'{{ $player->name }}')">
                                                <i class="fas fa-bolt" aria-hidden="true"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    @if(!$count)
                        <tr>
                            <td>
                                <div class='alert alert-dark m-0'>No players on list</div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class='col-xs-12 col-md-4 mt-3'>
            <table id='maybe' class='table table-striped table-bordered text-center bg-white shadow-sm'>
                <thead>
                    <tr>
                        <th class='bg-warning'>Maybe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 0 ?>
                    @if (count($lineup))
                        @foreach ($lineup as $player)
                            @if ($player->status === 'M')
                                <?php  $count++ ?>
                                <tr>
                                    <td class='table-warning d-flex align-items-center'>
                                        <div>{{ $player->name }}</div>
                                        @if ($auth)
                                            <button type="button" class="btn btn-sm btn-primary ml-auto" onclick="choice({{ $player->player_id }},'{{ $player->name }}')">
                                                <i class="fas fa-bolt" aria-hidden="true"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    @if(!$count)
                        <tr>
                            <td>
                                <div class='alert alert-dark m-0'>No players on list</div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class='col-xs-12 col-md-4 mt-3'>
            <table id='out' class='table table-striped table-bordered text-center bg-white shadow-sm'>
                <thead>
                    <tr>
                        <th class='bg-danger text-light'>Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 0 ?>
                    @if (count($lineup))
                        @foreach ($lineup as $player)
                            @if ($player->status === 'O')
                                <?php  $count++ ?>
                                <tr>
                                    <td class='table-danger d-flex align-items-center'>
                                        <div>{{ $player->name }}</div>
                                        @if ($auth)
                                            <button type="button" class="btn btn-sm btn-primary ml-auto" onclick="choice({{ $player->player_id }},'{{ $player->name }}')">
                                                <i class="fas fa-bolt" aria-hidden="true"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    @if(!$count)
                        <tr>
                            <td>
                                <div class='alert alert-dark m-0'>No players on list</div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class='col-12 mt-3'>
            <table id='no-response' class='table table-striped table-bordered text-center bg-white shadow-sm'>
                <thead>
                    <tr>
                        <th class='bg-dark text-light'>No Response</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 0;
                    @endphp
                    @if (count($roster))
                        @foreach ($roster as $player)
                            @php
                                $onList = 0;

                                foreach($lineup as $playerInLineup){
                                    if( $player->id === $playerInLineup->player_id ){
                                        $onList = 1;
                                        break;
                                    }
                                }
                            @endphp
                            @if (!$onList)
                                @php
                                    $count++;
                                @endphp
                                <tr>
                                    <td class='table-secondary d-flex align-items-center'>
                                        <div>{{ $player->name }}</div>
                                        @if ($auth)
                                            <button type="button" class="btn btn-sm btn-primary ml-auto" onclick="choice({{ $player->id }},'{{ $player->name }}')">
                                                <i class="fas fa-bolt" aria-hidden="true"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    @if(!$count)
                        <tr>
                            <td>
                                <div class='alert alert-success m-0'>Everyone has responded</div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function choice($id,$name){
            toastr.options.positionClass = 'toast-top-full-width';
            toastr.options.preventDuplicates = true;
            toastr.info(`
                <div class='row'>
                    <div class='col-4'>
                        <button status='I' class='btn btn-success btn-block'>In</button>
                    </div>
                    <div class='col-4'>
                        <button status='M' class='btn btn-warning btn-block'>Maybe</button>
                    </div>
                    <div class='col-4'>
                        <button status='O' class='btn btn-danger btn-block'>Out</button>
                    </div>
                </div>
            `,`${ $name } is...`);

            $("[status]").click(function(){

                $.ajax({
                    url: '/lineup/{{ $event->id }}',
                    type: 'PUT',
                    data: {
                        _token: "{{ csrf_token() }}",
                        player_id: $id,
                        status: $(this).attr('status')
                    },
                    dataType: 'json',
                    success: json => {
                        if(json.Status){
                            window.location.reload();
                        } else {
                            
                        }
                    }
                })
            })
        }
    </script>

@endsection