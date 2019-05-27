<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Season;

class SeasonController extends Controller
{
    public function create(Request $request){
        $this->validate($request,[
            'name' => 'required|min:4'
        ]);

        $season = new Season;
        $season->name = $request->name;
        $season->save();

        return back();
    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'name' => 'required|min:4'
        ]);

        $season = Season::find($id);
        $season->name = $request->name;
        $season->save();
        
        $request->session()->flash('success', 'Successfully modified the season');

        return back();
    }

    public function delete(Request $request, $id){
        $season = Season::find($id);
        $season->delete();
        $request->session()->flash('success', 'Successfully deleted the season');

        return back();
    }
}
