@extends('layouts.auth')

@section('splash-description', 'What is Updat3r?')

@section('content')
    <h5>Updat3r is an auto updater API that you can use to easily distribute updates.</h5>
    <p>You can release updates using the Updat3r panel and retrieve or even download these updates by sending a request to the API.</p>
    <p>The reason that Updat3r was created is because I wasn't satisfied with the other APIs. The existing solutions had
        issues with corrupt download files and weren't as flexible, which is why I ultimately decided to create this project.</p>
@endsection

@section('switch-button')
    <a href="/login" class="footer-link">Login</a>
@endsection
