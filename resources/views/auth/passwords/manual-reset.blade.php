@extends('layouts.app')

@section('title','Reset Password')

@section('content')

<div class="row mt-5 mb-3 justify-content-center">
    <div class="col-sm-12 col-md-6">
        <form class="card" action="/users/reset-password" method="POST">
            @csrf
            <div class="card-header">
                Reset Password
            </div>
            <div class="card-body">
                @if (Session::has('success'))
                    <div class="alert alert-success">{!! Session::get('success') !!}</div>
                @endif
                @if (Session::has('failure'))
                    <div class="alert alert-danger">{!! Session::get('failure') !!}</div>
                @endif
                <div class="form-group">
                    <label for="old_password">Old Password</label>
                    <input type="password" minlength='6' class="form-control" name="old" id="old_password" required>
                    @if ($errors->has('old'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('old') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" minlength='6' class="form-control" name="password" id="password" required>
                    @if ($errors->has('password'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" minlength='6' class="form-control" name="password_confirmation" id="confirm-password" required>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

@endsection