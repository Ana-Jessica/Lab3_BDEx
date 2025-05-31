@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-body p-5">
                @yield('auth-content')
            </div>
        </div>
    </div>
</div>
@endsection