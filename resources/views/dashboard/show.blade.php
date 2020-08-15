@extends('layouts.dashboard')

@section('breadcrumb', $project->name)
@section('title', $project->name)

@section('content')
<div class="row">
    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12">
        <div class="card">
            <h5 class="card-header">Information
            </h5>
            <div class="card-body">
                @include('inc.messages')

                <p><strong>Updates: </strong> {{ count($project->updates) }}</p>
                <p><strong>API key: </strong>{{ $project->api_key }}</p>
                @isset($project->legacy_api_key)
                <p><strong>Legacy API key: </strong>{{ $project->legacy_api_key }}</p>
                @endisset
            </div>
        </div>
        <div class="card">
            <h5 class="card-header">Release update</h5>
            <div class="card-body">
                <form action="{{ route('updates.store', $project) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input name="project_id" type="hidden" value="{{ $project->id }}">

                        <label for="version">Version: </label>
                        <input class="form-control form-control-lg" id="version" name="version" type="text"
                            placeholder="1.0" autocomplete="off" required>
                        <p style="margin: 10px 0px 5px 0px;">Should this build be public right away?</p>
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-primary active">
                                <input type="radio" name="public" id="public" value="true" checked> Public
                            </label>
                            <label class="btn btn-primary">
                                <input type="radio" name="public" id="public" value="false"> Not public
                            </label>
                        </div>
                        <p style="margin: 5px 0px 5px 0px;">Is this a critical update?
                        </p>
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-primary">
                                <input type="radio" name="critical" id="critical" value="true"> Critical
                            </label>
                            <label class="btn btn-primary active">
                                <input type="radio" name="critical" id="critical" value="false" checked> Not critical
                            </label>
                        </div>
                        <br>
                        <label for="updatefile">File:</label>
                        <input class="form-control form-control-lg" id="updatefile" name="updatefile" type="file"
                            required>
                    </div>
                    <button type="submit" name="submitupdate" id="submitupdate"
                        class="btn btn-primary btn-lg btn-block">Release!
                    </button>
                </form>
            </div>
        </div>

        @foreach($project->updates->reverse() as $update)
        <div class="card">
            <h5 class="card-header">Version {{$update->version}}</h5>
            <div class="card-body">
                <p><strong>Released on:</strong> {{$update->created_at}}</p>
                <p><strong>Public update:</strong> {{$update->public == 1 ? "Yes" : "No"}}</p>
                <p><strong>Critical update:</strong> {{$update->critical == 1 ? "Yes" : "No"}}</p>

                <form action="{{ route('updates.destroy', $update) }}" method="POST">
                    @method('delete')
                    @csrf
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ver{{str_replace(".", "-", $update->version)}}">Change
                        update</button>

                    <button type="submit" class="btn btn-primary" name="delete"
                        style="background-color: #ff3d16;">Delete Update!</button>
                </form>
            </div>
        </div>

        <div class="modal fade" id="ver{{str_replace(".", "-", $update->version)}}" tabindex="-1" role="dialog"
            aria-labelledby="ver{{str_replace(".", "-", $update->version)}}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change version {{ $update->version }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('updates.update', $update) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="changeversion">Version:
                                </label>
                                <input class="form-control form-control-lg" id="changeversion" name="changeversion"
                                    type="text" placeholder="1.0" autocomplete="off"
                                    value="{{ $update->version }}" required>
                                <p style="margin: 10px 0px 5px 0px;">Should this build be public?
                                </p>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{$update->public == 1 ? "active" : ""}}">
                                        <input type="radio" name="changepublic" id="changepublic" value="true"
                                            {{$update->public == 1? "checked" : ""}}> Public
                                    </label>
                                    <label class="btn btn-primary {{$update->public == 1 ? "" : "active"}}">
                                        <input type="radio" name="changepublic" id="changepublic" value="false"
                                            {{$update->public == 1 ? "" : "checked"}}> Not
                                        public
                                    </label>
                                </div>
                                <p style="margin: 5px 0px 5px 0px;">Is this a critical update?
                                </p>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{$update->critical == 1 ? "active" : ""}}">
                                        <input type="radio" name="changecritical" id="changecritical" value="true"
                                            {{$update->critical == 1 ? "checked" : ""}}> Critical
                                    </label>
                                    <label class="btn btn-primary {{$update->critical == 1 ? "" : "active"}}">
                                        <input type="radio" name="changecritical" id="changecritical" value="false"
                                            {{$update->critical == 1 ? "" : "checked"}}> Not critical
                                    </label>
                                </div>
                            </div>
                            <button type="submit" name="submitchange" id="submitchange"
                                class="btn btn-primary btn-lg btn-block">Change update!
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @endforeach


    </div>
    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
        <div class="card">
            <h5 class="card-header">Management actions
            </h5>
            <div class="card-body">
                <form action="{{ route('projects.update', $project) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">New name:</label>
                        <input class="form-control form-control-lg" id="name" name="name" type="text"
                            placeholder="" autocomplete="off" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">CHANGE!
                    </button>
                </form>
                <hr>
                <form action="{{ route('projects.destroy', $project) }}" method="POST">
                    @method('delete')
                    @csrf
                    <label for="deleteproject">Deleting a project is irreversible, proceed with caution.
                        </p>
                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="deleteproject"
                            style="background-color: #ff3d16;">DELETE PROJECT!
                        </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection