<div>
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger ">
            <ul class="list-unstyled">
                @foreach ($errors->all() as $error)
                    <li class="font-13">* {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (Session::has('success'))
        <div class="alert alert-success  text-center  mb-5">
            <h5 class="text-success m-0">{{ Session::get('success') }}</h5>
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger  text-center  mb-5">
            <h5 class="text-danger m-0">{{ Session::get('error') }}</h5>
        </div>
    @endif
</div>
