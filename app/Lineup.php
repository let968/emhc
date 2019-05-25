<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lineup extends Model
{
    protected $table = 'lineup';
    protected $fillable = ['player_id','event_id','status'];
}
