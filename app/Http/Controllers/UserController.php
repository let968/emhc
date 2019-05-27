<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $users = User::leftJoin('roster','roster.id','=','users.roster_id')
                    ->select('users.*','roster.name as roster_name','roster.number')
                    ->get();
        $players = DB::table('roster')->whereNull('deleted_at')->get();

        return view('users/index',compact('users','players'));
    }

    public function form(){
        return view('users/form');
    }

    public function create(Request $request){
        $this->validate($request,[
            'email' => 'required|email',
            'name' => 'required|min:4',
            'roster_id' => 'numeric|nullable',
            'admin' => 'digits_between:0,1'
        ]);

        $user = new User;

        $user->email = $request->email;
        $user->name = $request->name;
        $user->roster_id = $request->roster_id;
        $user->password = Hash::make('temp123');
        $user->admin = $request->admin;

        $user->save();

        $request->session()->flash('success', 'User created.');

        return back();
    }

    public function update(Request $request, $id){
        $user = User::find($id);

        $user->roster_id = $request->roster_id;
        $user->admin = $request->admin;

        return [
            'Status' => $user->save()
        ];
    }

    public function updatePassword(Request $request){
        $this->validate($request, [
            'old' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $currentlyAdmin = Auth::user()->admin;
        $id = Auth::id();
        $user = User::find($id);

        $hashedPassword = $user->password;

        if (Hash::check($request->old, $hashedPassword)) {
            //Change the password
            $user->fill([
                'password' => Hash::make($request->password),
                'first_login' => 0
            ])->save();
 
            $request->session()->flash('success', 'Your password has been changed.');
 
            return back();
        }
 
        $request->session()->flash('failure', 'Your password has not been changed.');
 
        return back();
    }
}
