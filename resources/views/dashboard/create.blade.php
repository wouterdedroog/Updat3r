@extends('layouts.dashboard')

@section('breadcrumb', 'Create Project')
@section('title', 'Create Project')

@section('content')
<div class="card">
    <h5 class="card-header">Project Creator</h5>
    <div class="card-body">
        @include('inc.messages')
        <form action="{{ route('projects.store') }}" method="POST">
            @method('POST')
            @csrf
            <div class="form-group">
                <input class="form-control form-control-lg" id="project_name" name="project_name" type="text"
                    placeholder="Projectname" autocomplete="off" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Create</button>
        </form>
    </div>
</div>
@endsection