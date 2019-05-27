@extends('layouts.app')

@section('title','Admin - Seasons')

@section('content')
    @php
        use App\Season;
        use Illuminate\Support\Facades\Auth;

        $auth = Auth::user();

        if( !$auth || !$auth->admin){
            abort(403, 'Unauthorized action.');
        }

        $s = new Season;
        $seasons = $s->getSeasons();
    @endphp


    <div class="row mt-4 justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="mb-3">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createSeasonModal">Add Season</button>
            </div>
            <table class="table table-striped table-bordered text-center">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($seasons))
                        @foreach ($seasons as $season)
                            <tr>
                                <td class='align-middle'>{{ $season->name }}</td>
                                <td class='align-middle'>
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editSeasonModal" onclick="updateSeason('{{ json_encode($season) }}')">
                                        <i class="fa fa-pen" aria-hidden="true"></i>
                                    </button>
                                    <form  class='d-inline-block ml-3' action="/seasons/delete/{{ $season->id }}" method="post" onsubmit="deleteSeason(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    
                    @else
                        <tr>
                            <td colspan="2">
                                <div class="alert alert-warning m-0">No seasons added</div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for season-->
    <div class="modal fade" id="createSeasonModal" tabindex="-1" role="dialog" aria-labelledby="seasonModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="seasonModalLabel">Add Season</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id='create-season-form' class='row p-3' action="/seasons/create" method="POST">
                        @csrf
                        <div class="col-12">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class='col-12 text-center mt-5'>
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSeasonModal" tabindex="-1" role="dialog" aria-labelledby="seasonModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="seasonModalLabel">Edit Season</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id='edit-season-form' class='row p-3' action="/seasons/update" method="POST">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="col-12">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" required>
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
        function updateSeason($season){
            const season = JSON.parse($season);
            const form = $("#edit-season-form");            

            form.attr('action',`/seasons/update/${ season.id }`);
            $("[name=name]",form).val( season.name );

        }

        function deleteSeason(e) {
            if(!confirm('Are you sure you want to delete this season')){
                e.preventDefault();
                return false;
            }

            return true;
        }

        @if(Session::has('success'))
            toastr.success("{{  Session::get('success') }}")
        @endif
    </script>
@endsection