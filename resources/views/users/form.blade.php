@extends('layouts.app')

@section('title','Create User')

@section('content')
    @php
        use Illuminate\Support\Facades\Auth;
        use Illuminate\Support\Facades\DB;

        $auth = Auth::user();
        if( !$auth || !$auth->admin ){
            abort(403,'Unauthorized action.');
        }

        $players = DB::table('roster')->whereNull('deleted_at')->get();
    @endphp

    <div class="row mt-5 mb-3 justify-content-center">
        <div class="col-sm-12 col-md-6 col-lg-4">
            @if (Session::has('success'))
                <div class="alert alert-success">{!! Session::get('success') !!}</div>
            @endif
            @if (Session::has('failure'))
                <div class="alert alert-danger">{!! Session::get('failure') !!}</div>
            @endif
            <form class="card shadow" action="/users/create" method="POST">
                @csrf
                <div class='card-header'>Add User</div>
                <div class='card-body'>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelpId" placeholder="" required>
                        @if ($errors->has('email'))
                            <small id="emailHelpId" class="form-text text-danger">{{ $errors->first('email') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" name="name" minLength=4 id="name" aria-describedby="helpId" placeholder="" required>
                        @if ($errors->has('name'))
                            <small id="nameHelpId" class="form-text text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="roster">Roster Link</label>
                        <select class="form-control" name="roster_id" id="roster">
                            <option value=''></option>
                            @foreach ($players as $player)
                                <option value="{{ $player->id }}">{{ $player->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="admin">Admin</label>
                      <select class="form-control" name="admin" id="admin" required>
                        <option value=''>-- Select --</option>
                        <option value='0' class='text-danger'>No</option>
                        <option value='1' class='text-success'>Yes</option>
                      </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection