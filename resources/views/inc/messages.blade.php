@if ($errors->any())
    @foreach($errors->all() as $errorMsg)
        <div class="alert alert-danger">
            {{$errorMsg}}
        </div>
    @endforeach
@endif

@isset($success)
    <div class="alert alert-success">
        {{$success}}
    </div>
@endisset

@isset($error)
    <div class="alert alert-danger">
        {{$error}}
    </div>
@endisset

@if(session('error'))
    <div class="alert alert-danger">
        {{session('error')}}
    </div>
@endif