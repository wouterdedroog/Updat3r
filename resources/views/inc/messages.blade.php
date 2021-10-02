@if ($errors->any())
    @foreach($errors->all() as $errorMsg)
        <div class="alert alert-danger">
            {{$errorMsg}}
        </div>
    @endforeach
@endif

@if(isset($success) || session('success'))
    <div class="alert alert-success">
        {{ $success ?? session('success') }}
    </div>
@endif

@if(isset($error) || session('error'))
    <div class="alert alert-danger">
        {{ $error ?? session('error') }}
    </div>
@endif
