@extends('layouts.app')
@section('content')
    @if (auth()->user()->is_single() && !auth()->user()->is_subdairy())
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <p>
                                You want to upgrade your account type from single user top multiple User Type.
                            </p>
                            <a href="{{ route('user.profile.upgrade') }}"
                                class="btn btn-sm btn-primary float-right">Request</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-6 col-md-4 grid-margin">
            <div class="card primary-box-shadow">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset(getProfileImage($user->user_id)) }}" alt="profile"
                            class="img-lg rounded-circle mb-3">
                        <div class="mb-3">
                            <h3>{{ $user->name }}</h3>
                            <p class="mb-1 text-center">{{ $user->profile->dairy_name }}</p>
                            <p class="mb-1 text-center">{{ $user->profile->address }}</p>
                            <div class="p-4">
                                <p class="clearfix">
                                    <span class="float-left">
                                        Plan Status
                                    </span>
                                    <span class="float-right text-muted">
                                        {{ !$user->planexpired() ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                                <p class="clearfix">
                                    <span class="float-left">
                                        Phone
                                    </span>
                                    <span class="float-right text-muted">
                                        {{ $user->country_code . ' ' . $user->mobile }}
                                    </span>
                                </p>
                                <p class="clearfix">
                                    <span class="float-left">
                                        Mail
                                    </span>
                                    <span class="float-right text-muted">
                                        {{ $user->email ?? 'NA' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('user.profile.edit') }}" class="btn btn-sm btn-primary">@lang('lang.:name Edit', ['name' => __('constants.Profile')])</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-8 grid-margin">
            <div class="card primary-box-shadow">
                <div class="card-body">
                    <form action="{{ route('user.profile.password.update') }}" method="POST">
                        @csrf
                        <h4 class="card-title">@lang('lang.:name Update', ['name' => __('lang.Password')])</h4>
                        <div class="form-group">
                            <label for="old_password">@lang('lang.Old Password')</label>
                            <div class="costum-input-password">
                                <input class="form-control" type="password" id="old_password" name="old_password"
                                    placeholder="old password">
                                <span id="password-icon" class="password-icon">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                            @error('old_password')
                                <label id="old_password-error" class="error mt-2 text-danger"
                                    for="old_password">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">@lang('lang.Password')</label>
                            <div class="costum-input-password">
                                <input class="form-control" type="password" id="password" name="password"
                                    placeholder="password">
                                <span id="password-icon" class="password-icon">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                            @error('password')
                                <label id="password-error" class="error mt-2 text-danger"
                                    for="password">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">@lang('lang.Confirm Password')</label>
                            <div class="costum-input-password">
                                <input class="form-control" type="password" id="confirm_password" name="confirm_password"
                                    placeholder="password">
                                <span id="password-icon" class="password-icon">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                            @error('confirm_password')
                                <label id="confirm_password-error" class="error mt-2 text-danger"
                                    for="confirm_password">{{ $message }}</label>
                            @enderror
                        </div>
                        <button class="btn btn-sm btn-primary form-control" type="submit">
                            @lang('lang.update')
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @if ($plan_recharges->count() > 0)
            <div class="col-sm-6 col-md-12 grid-margin">
                <div class="card primary-box-shadow">
                    <div class="card-body">
                        <h4 class="card-title">Plans Recharge</h4>
                        <ul class="bullet-line-list">
                            @foreach ($plan_recharges as $rech)
                                <li
                                    @if ($rech->payment_status == 1) class="lib_default"
                                @elseif($rech->payment_status == 2) class="lib_success"
                                @else class="lib_error" @endif>
                                    <h6>{{ $rech->plan->name }} ( {{ $rech->plan->category }})</h6>
                                    <p>{{ $rech->plan->description }}</p>
                                    <p class="text-muted mb-4">
                                        <i class="ti-time"></i>
                                        {{ getRechageDiff($rech->created_at) }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('user.plans.list') }}" class="btn btn-sm btn-primary">View More</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('styles')
    <style>
        .primary-box-shadow {
            box-shadow: #0066B7 0px 2px;
        }
    </style>
@endsection
