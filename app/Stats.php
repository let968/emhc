<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stats extends Model
{
    protected $fillable = ['event_id','player_id','goals','assists','blocks','pims'];

    public function pointLeaders(){
        $season = DB::table('event')->orderBy('date','desc')->first();

        $wins = DB::table('event')
                        ->whereColumn('our_score','>','opponent_score')
                        ->where('season',$season->season)
                        ->count();
        $losses = DB::table('event')
                        ->whereColumn('opponent_score','>','our_score')
                        ->where('season',$season->season)
                        ->count();


        $leaders = $this::select(DB::raw('roster.name,SUM(goals) as goals,SUM(assists) as assists,SUM(goals) + SUM(assists) as points'))
                        ->join('roster','roster.id','=','player_id')
                        ->join('event','event.id','=','event_id')
                        ->groupBy('roster.name')
                        ->orderBy(DB::raw('SUM(goals) + SUM(assists)'),'desc')
                        ->orderBy('roster.name','asc')
                        ->where('event.season', $season->season)
                        ->limit(5)
                        ->get();

        return (object)[
            'season' => $season->season,
            'record' => (object)[
                'wins' => $wins,
                'losses' => $losses
            ],
            'leaders' => $leaders
        ];
    }
}
