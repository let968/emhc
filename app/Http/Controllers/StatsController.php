<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Schedule;
use App\Stats;
use App\Lineup;

class StatsController extends Controller
{
    public function index($id){
        $event = Schedule::findOrFail($id)
                            ->leftJoin('location','location.id','=','event.location')
                            ->select('event.*','location.name')
                            ->where('event.id',$id)
                            ->orderBy('date','desc')
                            ->get()[0];

        $stats = Lineup::leftJoin('stats',function($q){
                            $q->on('lineup.event_id','=','stats.event_id')
                                ->on('lineup.player_id','=','stats.player_id');
                        })
                        ->join('roster','roster.id','=','lineup.player_id')
                        ->select('stats.*','lineup.player_id','lineup.event_id','roster.name','roster.number')
                        ->where('lineup.event_id',$id)->where('lineup.status','I')
                        ->orderBy(DB::raw('(stats.goals + stats.assists)'),'desc')
                        ->get();

        return view('stats/index',compact('stats','event'));
    }

    public function changePlayerStats(Request $request, $id){
        $status = Stats::updateOrCreate(
            ['player_id' => $request->player_id, 'event_id' => $id ],
            [
                'goals' => $request->goals,
                'assists' => $request->assists,
                'blocks' => $request->blocks,
                'pims' => $request->pims
            ]
        );

        return ['Status' => $status ? 1 : 0];
    }
}
