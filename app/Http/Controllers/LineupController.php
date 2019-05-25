<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lineup;
use App\Schedule;
use App\Roster;

class LineupController extends Controller
{
    public function index($id){
        $event = Schedule::findOrFail($id)
                            ->leftJoin('location','location.id','=','event.location')
                            ->select('event.*','location.name')
                            ->where('event.id',$id)
                            ->orderBy('date','desc')
                            ->get()[0];

        $roster = Roster::whereNull('deleted_at')
                            ->where('status','A')
                            ->orderBy('name','asc')
                            ->get();

        $lineup = Lineup::where('event_id',$id)
                            ->leftJoin('roster','roster.id','=','lineup.player_id')
                            ->select('lineup.*','roster.name')
                            ->orderBy('roster.name','asc')
                            ->get();
        
        return view('lineup/index',compact('event','roster','lineup'));
    }

    public function changePlayerStatus(Request $request, $id){
        $status = Lineup::updateOrCreate(
            ['player_id' => $request->player_id, 'event_id' => $id ],
            ['status' => $request->status]
        );

        return ['Status' => $status ? 1 : 0];
    }
}
