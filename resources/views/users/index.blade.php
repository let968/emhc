@extends('layouts.app')

@section('title','Users')

@section('content')

    @php
        use Illuminate\Support\Facades\Auth;

        $auth = Auth::user();
        if( !$auth || !$auth->admin ){
            abort(403,'Unauthorized action.');
        }

        
    @endphp

    <div class='row mt-3 mb-3'>
        <div class='col-12'>
            <a class="btn btn-primary" href="/users/create" role="button">Create User</a>
        </div>
        <div class='col-12 mt-3'>
            <table class='table table-striped text-center'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roster Link</th>
                        <th>Admin</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($users))
                        @foreach ($users as $user)
                            <tr>
                                <td class='align-middle'>{{ $user->name }}</td>
                                <td class='align-middle'>{{ $user->email }}</td>
                                <td class='align-middle'>
                                    @if ($user->roster_id)
                                        <h4 class='m-0'>
                                            <span class='badge bg-team-primary text-team-tertiary pt-1 pb-1 pl-2 pr-2'>{{ $user->number }} - {{ $user->roster_name }}</span>
                                        </h4>
                                    @endif
                                </td>
                                <td class='align-middle'>
                                    @if ($user->admin)
                                        <span class="text-success">Yes</span>
                                    @else
                                        <span class="text-danger">No</span>
                                    @endif
                                </td>
                                <td class='align-middle'>
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#userModal" onclick="updateUser('{{ json_encode($user) }}')">
                                        <i class='fas fa-pen'></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for user-->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id='edit-user-form' class='row p-3 needs-validation' novalidate>
                        @csrf
                        <div class="col-12">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" disabled>
                        </div>
                        <div class="col-12 mt-3">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" disabled>
                        </div>
                        <div class="col-12 mt-3">
                            <label>Player Link</label>
                            <select class="form-control" name="roster_id">
                                <option value=''>-- SELECT --</option>
                                @foreach ($players as $player)
                                    <option value="{{ $player->id }}">{{ $player->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <label>Admin</label>
                            <select class="form-control" name="admin" required>
                                <option value=''>-- SELECT --</option>
                                <option value="0" class='text-danger'>No</option>
                                <option value="1" class='text-success'>Yes</option>
                            </select>
                        </div>
                        <div class='col-12 text-center mt-5'>
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateUser($user){
            let user = JSON.parse($user);
            let form = $("#edit-user-form");

            $("[name=name]",form).val( user.name );
            $("[name=email]",form).val( user.email );
            $("[name=roster_id]",form).val( user.roster_id );
            $("[name=admin]",form).val( user.admin );
            
            form.submit(e => {
                e.preventDefault();

                $.ajax({
                    url: `/users/${ user.id }`,
                    type: 'PUT',
                    data: form.serialize(),
                    dataType: 'json',
                    success: json => {
                        if( json.Status ){
                            window.location.reload();
                        } else {
                            toastr.error('An error has occured');
                        }
                    }
                })
            })
        }
    </script>
    
@endsection