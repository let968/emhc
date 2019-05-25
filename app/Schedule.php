<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'date',
        'start_time',
        'our_score',
        'opponent_score',
        'season',
        'type'
    ];
    
    function __construct(){
        $this->table = 'event';
    }
}
