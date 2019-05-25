<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Schedule;

class ScheduleController extends Controller
{
    public function index(){
        $events = Schedule::
                            leftJoin('location','location.id','=','event.location')
                            ->select('event.*','location.name')
                            ->orderBy('date','desc')->get();

        
        return view('schedule/index',compact('events'));
    }

    public function create(Request $request){
        $event = new Schedule;

        $event->date            = $request->date;
        $event->start_time      = $request->time;
        $event->opponent        = $request->opponent;
        $event->location        = $request->location;
        $event->our_score       = $request->our_score ?: '0';
        $event->opponent_score  = $request->opponent_score ?: '0';
        $event->season          = $request->season;
        $event->type            = $request->type;

        return [
            'Status' => $event->save() ? 1 : 0,
            'Message' => 'message'
        ];
    }

    public function form($id=0){
        if( $id ){
            $event = Schedule::findOrFail($id)
                                ->where('id',$id)
                                ->get()[0];
        } else {
            $event = false;
        }

        $opponents = Schedule::select("opponent")->distinct()->orderBy('opponent','asc')->get();
        $locations = DB::table('location')->orderBy('name','asc')->get();
        
        return view('schedule/create',compact('opponents','locations','event'));
    }

    public function update(Request $request, $id){
        $event = Schedule::where('id',$id)
                    ->update([
                        'date'              => $request->date,
                        'start_time'        => $request->time,
                        'our_score'         => $request->our_score ?: 0,
                        'opponent_score'    => $request->opponent_score ?: 0,
                        'season'            => $request->season,
                        'type'              => $request->type,
                        'opponent'          => $request->opponent,
                        'location'          => $request->location
                    ]);

        return [ 'Status' => $event ];
    }
}
