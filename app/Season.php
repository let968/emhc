<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{

    protected $table = 'season';

    public function getSeasons(){
        $seasons = Season::orderBy('name','asc')->get();

        return $seasons;
    }

    public function getCurrentSeason(){
        $season = Season::
                    leftJoin('event','season.id','=','event.season')
                    ->select('season.id','season.name')
                    ->orderBy('date','desc')->first();

        return $season;
    }
}
