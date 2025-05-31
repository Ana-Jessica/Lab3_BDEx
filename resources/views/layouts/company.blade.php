@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('company.components.sidebar')
    </div>
    <div class="col-md-9">
        @yield('company-content')
    </div>
</div>
@endsection