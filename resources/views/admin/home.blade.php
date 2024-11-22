@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-12 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Welcome {{ auth()->user()->name }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <a href="https://mydairy.digital/public/management/app-user" class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-account text-primary icon-lg"></span>
                        <div class="ms-3 my-2 text-center">
                            <h6 class="text-primary">Total User : {{ $total_user }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="https://mydairy.digital/public/management/app-user" class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-account-star text-primary icon-lg"></span>
                        <div class="ms-3 my-2 text-center">
                            <h6 class="text-primary">Active User : {{ $total_user - $blocked_user }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="https://mydairy.digital/public/management/app-user" class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-account-cancel text-primary icon-lg"></span>
                        <div class="ms-3 my-2 text-center">
                            <h6 class="text-primary">Inactive User : {{ $blocked_user }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.plans.list') }}" class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-card-account-details text-primary icon-lg"></span>
                        <div class="ms-3 my-2 text-center">
                            <h6 class="text-primary">Total Plans : {{ $total_plan }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.plans.list') }}" class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-card-account-details text-primary icon-lg"></span>
                        <div class="ms-3 my-2 text-center">
                            <h6 class="text-primary">Active Plans : {{ $active_plan }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="" class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-currency-rupee text-primary icon-lg"></span>
                        <div class="ms-3 my-2 text-center">
                            <h6 class="text-primary">Total Payment : &#8377; {{ number_format($total_payment, 2) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="" class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-currency-rupee text-primary icon-lg"></span>
                        <div class="ms-3 my-2 text-center">
                            <h6 class="text-primary">Today Payment : &#8377; {{ number_format($today_payment, 2) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endsection
@section('styles')
    <style>
        .card-border {
            border: 1px solid #007bff;
        }
    </style>
@endsection
