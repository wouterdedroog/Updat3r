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
                <p>To use the API, you need to provide the API key. These keys are unique for each project, you can find
                    these keys on your project page.</p>
                <div class="codeheader" id="codeheader_curl">https://updates.mrwouter.nl/api/v2/updates/[project]/latest
                </div>
                <div id="codebox">
                    <p>You can provide 2 variables when using this endpoint. 'project' and 'show'. Project is required,
                        but 'show' isn't. When the 'show' variable isn't provided, all updates are returned. 'show' can
                        also have a value of 'latest' to only show the latest update, or an integer to show the last X
                        updates.</p>
                    <p>When using the updates endpoint, you'll have to send a GET request and provide the 'project'
                        variable.
                        You can use cURL to see if the API is functioning as it should. Let's try a project called
                        'testproject'.</p>
                    <code>curl "https://updates.mrwouter.nl/api/v2/updates/testproject/latest" -H "Authorization: Bearer fb7ccca9-646a-476c-a0e9-27106e471163"</code>
                    <p style="margin-top: 15px; margin-bottom: 5px;">cURL returns the following JSON:</p>
                    <code>{"status":200,"debug":{"count":1,"executiontime":"6ms"},"updates":[{"version":"1.0","download":"https://updates.mrwouter.nl/api/v2/updates/download/?project=testproject&key=228WtrxSBnnQeSexQet6RK6yFurdxgLj&ver=1.0","releaseDate":"2019-03-30 13:51:39","critical":true}]}</code>

                    <p style="margin-top: 15px; margin-bottom: 5px;">Status says something about whether the API call
                        was a success. If the status code is 200, there are no errors. If an error occurs, the status
                        code is 400. With a statuscode of 400 there's a 'message' object included.</p>
                    <p style="margin-top: 13px; margin-bottom: 5px;">Every data within the 'debug' object is not
                        important for normal use. It can be used to verify the input. The 'count' object returns '1'
                        when the 'show' variable is latest, and '-1' for all updates (when the show isn't used).</p>
                    <p style="margin-top: 13px; margin-bottom: 5px;">The JSON array 'updates' is quite logical. It shows
                        the versionnumber, downloadlink, releasedate and if the update has been marked as 'critical'.
                    </p>
                </div>

                <div class="codeheader" id="codeheader_curl">
                    https://updates.mrwouter.nl/api/v1/updates/download/?project=[project]&key=[key]&ver=[version]</div>
                <div id="codebox">
                    <p>The variables 'project' and 'ver' are both required. They tell the system what version of what
                        project needs to be downloaded.</p>
                    <p>When using the download endpoint, you'll have to send a GET request. You can test this endpoint
                        by visiting the link below.</p>
                    <code>https://updates.mrwouter.nl/api/v1/updates/download/?project=testproject&key=228WtrxSBnnQeSexQet6RK6yFurdxgLj&ver=1.0</code>
                    <p style="margin-top: 15px; margin-bottom: 5px;">If all the provided information is correct, you
                        should be able to download the file. In some cases an error may occur. This will provide you
                        with a JSON output containing a 'status' object and a 'message' object. For example:</p>
                    <code>{"status":400,"message":"There's no project called somerandomproject!"}</code>
                </div>
                <p>Enough vague talking, time for some examples.</p>
                <div class="codeheader" id="codeheader_java">(Java) Using {{ config('app.name', 'Updat3r') }} for your
                    Minecraft plugin</div>
                <script src="https://gist.github.com/MrWouterNL/a0a7ec20fcc0df157fc8290ec6055cba.js"></script>
            </div>
        </div>
    </div>
</div>
@endsection