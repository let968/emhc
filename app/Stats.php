<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    protected $fillable = ['event_id','player_id','goals','assists','blocks','pims'];
}
