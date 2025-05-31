@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('developer.components.sidebar')
    </div>
    <div class="col-md-9">
        @yield('developer-content')
    </div>
</div>
@endsection