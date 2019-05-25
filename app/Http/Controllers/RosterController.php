<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Roster;


class RosterController extends Controller
{
    public function index(){
        $players = Roster::whereNull('deleted_at')->orderBy('name','asc')->get();

        return view('roster/index',compact('players'));
    }

    public function form($id=0){

        if( $id ){
            $player = Roster::findOrFail($id)
                                ->where('id',$id)
                                ->get()[0];
        } else {
            $player = false;
        }

        return view('roster/add',compact('player'));
    }

    public function addOrUpdate(Request $request, $id=0){
        if( $id ){
            $result = Roster::where('id',$id)
                                ->update([ 'number' => $request->number, 'name' => $request->name ]);
        } else {
            $player = new Roster;

            $player->name = $request->name;
            $player->number = $request->number;
            $result = $player->save();
        }

        return [ 'Status' => $result ? 1 : 0 ];
    }

    public function softDelete(Request $request,$id){
        $player = Roster::find($id)->delete();
        return ['Status' => $player ? 1 : 0];
    }
}
