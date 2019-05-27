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
}
