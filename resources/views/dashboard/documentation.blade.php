@extends('layouts.dashboard')

@section('title', 'Documentation')
@section('breadcrumb', 'Documentation')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header">Methods</h5>
            <div class="card-body">
                <p>{{ config('app.name', 'Updat3r') }} currently provides 2 API endpoints. One for getting all the
                    updates, and one for downloading
                    the update.</p>
                <p>To use the API, you need to provide the API key through the Authorization header. These keys are unique for each project, you can find
                    these keys on your project page.</p>
                <div class="codeheader" id="codeheader_curl">
                    {{ config('app.url', 'https://updates.mrwouter.nl') }}/api/v2/updates/[project]/[amount]
                </div>
                <div id="codebox">
                    <p>You can provide 2 variables when using this endpoint. 'project' and 'amount'. Both are required.
                        The amount variable can be an integer or 'latest', to only return the latest update.</p>
                    <p>When using the updates endpoint, you'll have to send a GET request and provide the 'project' and
                        'amount'
                        variable.
                        You can use cURL to see if the API is functioning as it should. Let's try a project called
                        'testproject'.</p>
                    <code>curl "{{ config('app.url', 'https://updates.mrwouter.nl') }}/api/v2/updates/testproject/latest" -H "Authorization: Bearer fb7ccca9-646a-476c-a0e9-27106e471163"</code>
                    <p style="margin-top: 15px; margin-bottom: 5px;">cURL returns the following JSON:</p>
                    <code>{"status":200,"updates":[{"version":"1.0","download":"{{ config('app.url', 'https://updates.mrwouter.nl') }}/api/v2/updates/download/testproject/1.0","releaseDate":"2020-08-16 08:54:38","critical":false}]}</code>

                    <p style="margin-top: 15px; margin-bottom: 5px;">Status says something about whether the API call
                        was a success. If the status code is 200, there are no errors. If an error occurs, the status
                        code is 400. With a statuscode of 400 there's a 'message' object included.</p>
                    <p style="margin-top: 13px; margin-bottom: 5px;">The JSON array 'updates' is quite simple. It shows
                        the version, downloadlink, releasedate and if the update has been marked as 'critical'.
                    </p>
                </div>

                <div class="codeheader" id="codeheader_curl">
                    {{ config('app.url', 'https://updates.mrwouter.nl') }}/api/v2/updates/download/[project]/[version]
                </div>
                <div id="codebox">
                    <p>The variables 'project' and 'version' are both required. They tell the system what version of what
                        project needs to be downloaded.</p>
                    <p>When using the download endpoint, you'll have to send a GET request. You can test this endpoint
                        by visiting using cURL.</p>
                        <code>curl "{{ config('app.url', 'https://updates.mrwouter.nl') }}/api/v2/updates/download/testproject/1.0" -H "Authorization: Bearer fb7ccca9-646a-476c-a0e9-27106e471163"</code>
                    <p style="margin-top: 15px; margin-bottom: 5px;">If all the provided information is correct, you
                        are able to download the file. In some cases an error may occur. This will provide you
                        with a JSON output containing a 'status' object and a 'message' object. For example:</p>
                    <code>{"status":400,"message":"There's no project called somerandomproject!"}</code>
                </div>
                <div class="codeheader" id="codeheader_java">(Java) Using {{ config('app.name', 'Updat3r') }} for your
                    Minecraft plugin</div>
                <script src="https://gist.github.com/wouterdedroog/a0a7ec20fcc0df157fc8290ec6055cba.js"></script>
            </div>
        </div>
    </div>
</div>
@endsection
