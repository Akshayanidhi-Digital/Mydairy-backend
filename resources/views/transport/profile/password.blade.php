@extends('layouts.transport')
@section('content')
    <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">
                    <form action="{{ route('transport.profile.password.update') }}" method="POST">
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
    </div>
@endsection
