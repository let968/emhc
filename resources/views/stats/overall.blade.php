@php
    use App\Season;
    use App\Stats;
    use Illuminate\Support\Facades\DB;

    $stat = new Stats;
    $overallStats = $stat->overallStats($season);

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

@endphp

@extends('layouts.app')

@section('title','Overall Stats')

@section('content')
    <div class='row mt-4 mb-4'>
        <div class='col-lg-4'>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Season</span>
                </div>
                <select name="seasonFilter" id="" class="form-control" onchange="window.location.assign(`/stats/overall/${ this.value }`)">
                    <option value=""></option>
                    @foreach ($all_seasons as $s)
                        <option value="{{ $s->season }}"
                            {{ $s->season == $season ? 'selected' : '' }}
                            >{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class='col-12'>
            <div class='table-responsive'>
                <table class='table table-striped table-bordered text-center'>
                    <thead>
                        <tr class='bg-dark text-light'>
                            <th>#</th>
                            <th>Name</th>
                            <th>GP</th>
                            <th>G</th>
                            <th>A</th>
                            <th>P</th>
                            <th>Blk</th>
                            <th>PIM</th>
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
                        @foreach ($overallStats as $player)
                            @php
                                $total->goals   += $player->goals ?: 0;
                                $total->assists += $player->assists ?: 0;
                                $total->blocks  += $player->blocks ?: 0;
                                $total->pims    += $player->pims ?: 0;
                            @endphp
                            <tr>
                                <td class='align-middle'>{{ $player->number }}</td>
                                <td class='align-middle'>{{ $player->name }}</td>
                                <td class='align-middle'>{{ $player->games_played }}</td>
                                <td class='align-middle'>{{ $player->goals ?: '-' }}</td>
                                <td class='align-middle'>{{ $player->assists ?: '-' }}</td>
                                <td class='align-middle'>{{ $player->goals || $player->assists ? $player->goals + $player->assists : '-' }}</td>
                                <td class='align-middle'>{{ $player->blocks ?: '-' }}</td>
                                <td class='align-middle'>{{ $player->pims ?: 0 }}:00</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class='bg-dark text-light'>
                            <th colspan='3'></th>
                            <th>{{ $total->goals }}</th>
                            <th>{{ $total->assists }}</th>
                            <th>{{ $total->goals + $total->assists }}</th>
                            <th>{{ $total->blocks }}</th>
                            <th>{{ $total->pims }}:00</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@endsection