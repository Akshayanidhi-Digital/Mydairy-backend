@extends('layouts.auth')
@section('contant')
    <div class="row w-100 mx-0">
        <div class="col-lg-10 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5 rounded">
                <div class="brand-logo">
                    <img src="{{ asset('assets/panel/images/logo.svg') }}" alt="logo">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h4>Welcome back</h4>
                        <h6 class="font-weight-light">Sign in to continue as Transporter.</h6>
                        <form role="form" method="post" action="{{ route('transport.login') }}">
                            @csrf
                            <label>Mobile number</label>
                            <div class="mb-3">
                                <input type="text" id="mobile" name="mobile" class="form-control"
                                    value="{{ old('mobile') }}" placeholder="Mobile" aria-label="Mobile">
                                @error('mobile')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <label>Password</label>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password"
                                    aria-label="Password" aria-describedby="password-addon">
                                @error('password')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="my-2 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <label class="form-check-label text-muted">
                                        <input type="checkbox" class="form-check-input">
                                        Keep me signed in
                                        <i class="input-helper"></i></label>
                                </div>
                                <a href="javascript:void(0)" id="forgotPassword" class="auth-link text-black">Forgot
                                    password?</a>
                            </div>
                            <div class="text-center">
                                <button type="submit"
                                    class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Sign
                                    in</button>
                            </div>
                            <div class="text-center mt-4 font-weight-light">
                                <a href="{{ route('home') }}"
                                    class="text-info text-gradient h4 font-weight-bold text-uppercase">Back To Home</a>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column h-100 justify-content-center align-items-center">
                            <img src="{{ asset('assets/panel/images/transporter.png') }}" alt="Transporter Login Page">
                            <div class="template-demo mt-2">
                                <button class="btn btn-outline-dark btn-icon-text">
                                    <i class="ti-apple btn-icon-prepend"></i>
                                    <span class="d-inline-block text-left">
                                        <small class="font-weight-light d-block">Available on the</small>
                                        App Store
                                    </span>
                                </button>
                                <button class="btn btn-outline-dark btn-icon-text">
                                    <i class="ti-android btn-icon-prepend"></i>
                                    <span class="d-inline-block text-left">
                                        <small class="font-weight-light d-block">Get it on the</small>
                                        Google Play
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <style>
        .content-wrapper {
            background: rgb(0, 102, 183);
            background: linear-gradient(90deg, rgba(0, 102, 183, 1) 0%, rgba(26, 149, 233, 1) 47%, rgba(99, 180, 238, 1) 100%);
        }

        .container {
            background: #fff;
        }
    </style>
@endsection
@section('scripts')
    <script>
        (function($) {
            $('#forgotPassword').on('click', function() {
                var mobile = $('#mobile').val();
                if (mobile != '') {

                }
                let _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ route('transport.forgotPassword') }}",
                    type: "POST",
                    data: {
                        _token: _token,
                        mobile: mobile,
                        country_code:'+91',
                    },
                    success: function(response) {
                        if (response.success) {
                            swal.fire({
                                title: "Success",
                                text: response.message,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false,
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            swal.fire({
                                title: "Error",
                                text: response.message,
                                icon: "info",
                                timer: 2000,
                                showConfirmButton: false,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        swal.fire({
                            title: "@lang('lang.Oops!')",
                            text: "@lang('message.SOMETHING_WENT_WRONG')",
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                });
            });
        })(jQuery);
    </script>
@endsection
