@php
    use Illuminate\Support\Facades\Auth;
    $auth = Auth::user();

    if( !$auth || !$auth->admin ){
        abort(403, 'Unauthorized action.');
    }    
@endphp

@extends('layouts.app')

@section('title','Add Player')

@section('content')
    
    <div class="card mt-3 mb-3">
        <div class="card-header">
            Player
        </div>
        <div class="card-body">
            <form action="/roster/add" method="POST" class='row'>
                @csrf
                <div class='col-sm-12 col-lg-6 mt-3'>
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $player ? $player->name : '' }}" required>
                </div>
                <div class='col-sm-12 col-lg-6  mt-3'>
                    <label for="number">Number</label>
                    <input type="number" min='0' max='99' class="form-control" name="number" id="number" value="{{ $player ? $player->number : '' }}" required>
                </div>
                <div class='col-12 mt-5 text-center'>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>

<script>



    $('form').submit(function(e){
        e.preventDefault();

        $.ajax({
            url: '/roster/add{{ $player ? "/$player->id" : '' }}',
            type: "{{ $player ? 'PUT' : 'POST' }}",
            data: $('form').serialize(),
            dataType: 'json',
            success: json => {
                if( json.Status ){
                    window.location.assign('/roster');
                } else {
                    toastr.error('An error has occured');
                }
            }
        });
    });
</script>

@endsection