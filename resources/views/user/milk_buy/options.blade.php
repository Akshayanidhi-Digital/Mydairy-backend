@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-3 grid-margin stretch-card" onclick="milkBuyEntry()">
            <div class="card border border-primary">
                <div class="card-body">
                    <div class="d-sm-flex flex-column flex-wrap text-center text-sm-left align-items-center">
                        <img src="{{ asset('images/appicon/can.png') }}" class="app-img rounded" alt="profile image">
                        <h4 class="mt-4 text-primary">@lang('lang.Milk Procure')</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3 grid-margin stretch-card">
            <div class="card border border-primary">
                <div class="card-body">
                    <div class="d-sm-flex flex-column flex-wrap text-center text-sm-left align-items-center">
                        <img src="{{ asset('images/appicon/costumers.png') }}" class="app-img rounded" alt="profile image">
                        <h4 class="mt-4 text-primary">@lang('constants.customers')</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3 grid-margin stretch-card">
            <div class="card border border-primary">
                <div class="card-body">
                    <div class="d-sm-flex flex-column flex-wrap text-center text-sm-left align-items-center">
                        <img src="{{ asset('images/appicon/records.png') }}" class="app-img rounded" alt="profile image">
                        <h4 class="mt-4 text-primary">@lang('lang.Milk Records')</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3 grid-margin stretch-card">
            <div class="card border border-primary">
                <div class="card-body">
                    <div class="d-sm-flex flex-column flex-wrap text-center text-sm-left align-items-center">
                        <img src="{{ asset('images/appicon/ratechart.png') }}" class="app-img rounded" alt="profile image">
                        <h4 class="mt-4 text-primary">@lang('lang.Rate Charts')</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3 grid-margin stretch-card">
            <div class="card border border-primary">
                <div class="card-body">
                    <div class="d-sm-flex flex-column flex-wrap text-center text-sm-left align-items-center">
                        <img src="{{ asset('images/appicon/settings.png') }}" class="app-img rounded" alt="profile image">
                        <h4 class="mt-4 text-primary">@lang('constants.Settings')</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <style>
        .app-img {
            height: 4rem;
            width: auto;
        }
    </style>
@endsection