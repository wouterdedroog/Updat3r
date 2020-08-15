@extends('layouts.auth')

@section('splash-description', 'What is Updat3r?')

@section('content')
<h5>Updat3r is an autoupdater API that you can use to easily distribute updates. You can release updates using the
    Updat3r panel. You can get a list of these updates by sending a request to the API.</h5>
<p>The reason that I've created Updat3r is because I wasn't satisfied with the other APIs. For Minecraft plugins, there
    is an API called Spiget. Spiget is an amazing API, but falls short when you're trying to download your plugin. This
    is because Spiget needs to automatically download every resource, and Cloudflare tends to get in the way every now
    and then and corrupts your download.</p>
@endsection

@section('switch-button')
<a href="/login" class="footer-link">Login</a>
@endsection