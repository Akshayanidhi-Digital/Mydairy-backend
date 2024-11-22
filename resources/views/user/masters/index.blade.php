@extends('layouts.app')
@section('content')
    <div class="row">
        <a href="{{ route('user.masters.roles.list') }}" class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-tale">
                <div class="card-body">
                    <p class="mb-4">@lang('lang.Roles')</p>
                    <p class="fs-30 mb-2">4006</p>
                </div>
            </div>
        </a>
        <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <div class="card-body">
                    <p class="mb-4">@lang('lang.Routes')</p>
                    <p class="fs-30 mb-2">61344</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-tale">
                <div class="card-body">
                    <p class="mb-4">@lang('lang.Transporters')</p>
                    <p class="fs-30 mb-2">4006</p>
                </div>
            </div>
        </div>
        <a href="{{route('user.childUser.list')}}" class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <div class="card-body">
                    <p class="mb-4">@lang('lang.Child Dairy')</p>
                    <p class="fs-30 mb-2">61344</p>
                </div>
            </div>
        </a>
    </div>
@endsection
