<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stats extends Model
{
    protected $fillable = ['event_id','player_id','goals','assists','blocks','pims'];

    public function pointLeaders(){
        $season = DB::table('event')->orderBy('date','desc')->first();

        $wins = DB::table('event')->select(DB::raw('COUNT(id) AS wins'))->where('our_score','>','opponent_score')->where('season',$season->season)->first()->wins;
        $losses = DB::table('event')->select(DB::raw('COUNT(id) AS losses'))->where('our_score','<','opponent_score')->where('season',$season->season)->first()->losses;

        $leaders = $this::select(DB::raw('roster.name,SUM(goals) as goals,SUM(assists) as assists,SUM(goals) + SUM(assists) as points'))
                        ->join('roster','roster.id','=','player_id')
                        ->join('event','event.id','=','event_id')
                        ->groupBy('player_id')
                        ->orderBy(DB::raw('SUM(goals)'))
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
