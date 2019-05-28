<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Season;

class Stats extends Model
{
    protected $fillable = ['event_id','player_id','goals','assists','blocks','pims'];

    public function pointLeaders(){
        $s = new Season;
        $season = $s->getCurrentSeason();
        

        $wins = DB::table('event')
                        ->whereColumn('our_score','>','opponent_score')
                        ->where('season',$season->season)
                        ->count();
        $losses = DB::table('event')
                        ->whereColumn('opponent_score','>','our_score')
                        ->where('season',$season->season)
                        ->count();

        $nextGame = DB::table('event')
                        ->join('location','location.id','=','event.location')
                        ->select('event.*','location.name')
                        ->whereRaw('date >= NOW()')
                        ->orderBy('date','asc')
                        ->limit(1)
                        ->get();

        $prevGame = DB::table('event')
                        ->join('location','location.id','=','event.location')
                        ->select('event.*','location.name')
                        ->whereRaw('date <= NOW()')
                        ->orderBy('date','desc')
                        ->limit(1)
                        ->get();


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
            'season' => $season,
            'record' => (object)[
                'wins' => $wins,
                'losses' => $losses
            ],
            'games' => (object)[
                'next' =>  count($nextGame) ? $nextGame[0] : false,
                'prev' =>  count($prevGame) ? $prevGame[0] : false
            ],
            'leaders' => $leaders
        ];
    }

    public function overallStats($season=false){
        if(!$season){
            $s = new Season;
            $season = $s->getCurrentSeason()->id;
        }

        $stats = DB::table('roster')
                        ->select(DB::raw('roster.name,roster.number,count(stats.id) as games_played,SUM(goals) as goals,SUM(assists) as assists,SUM(blocks) as blocks,SUM(pims) as pims,SUM(goals) + SUM(assists) as points'))
                        ->leftJoin('stats','roster.id','=','player_id')
                        ->leftJoin('event','event.id','=','event_id')
                        ->groupBy('roster.name','roster.number')
                        ->orderBy(DB::raw('SUM(goals) + SUM(assists)'),'desc')
                        ->where('event.season',$season)
                        ->get();

        return $stats;
    }
}
