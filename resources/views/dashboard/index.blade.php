@extends('layouts.dashboard')

@section('title', 'Welcome ' . Auth::user()->name . '!')
@section('breadcrumb', 'Landing')

@section('content')
<div class="row">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="text-muted">Projects</h5>
                <div class="metric-value d-inline-block">
                    <h1 class="mb-1">{{ $user->projects()->count() }}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="text-muted">Updates</h5>
                <div class="metric-value d-inline-block">
                    <h1 class="mb-1">
                        {{ $user->updates()->count() }}
                    </h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header">Information</h5>
            <div class="card-body">
                <p>{{config('app.name', 'Updat3r')}} is an autoupdater API that you can use to easily distribute
                    updates. You
                    can release updates using the {{config('app.name', 'Updat3r')}} panel.
                    You can get a list of these updates by <a href="/documentation">sending a
                        request to the API.</a>
                    {{config('app.name', 'Updat3r')}} tries to make an reliable and easy way to host your updates with a
                    reliable API.</p>
            </div>
        </div>
    </div>
</div>
@endsection
