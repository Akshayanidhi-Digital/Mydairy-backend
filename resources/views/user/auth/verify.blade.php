@extends('layouts.auth')
@section('contant')
    <div class="row w-100 mx-0">
        <div class="col-lg-6 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5 rounded">
                <div class="brand-logo">
                    <img src="{{ asset('assets/panel/images/logo.svg') }}" alt="logo">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Verify</h4>
                        <p class="font-weight-light">An OTP (One-Time Password) has been sent to your mobile number.</p>
                        <p class="font-weight-light">Please enter the OTP below to verify your number.</p>
                        <form role="form" method="post" action="{{ route('verify.post') }}">
                            @csrf
                            <label>Mobile number</label>
                            <div class="mb-3">
                                <input type="text" name="mobile" class="form-control"
                                    value="{{ old('mobile', session()->get('mobile')) }}" placeholder="Mobile"
                                    aria-label="Mobile">
                                @error('mobile')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <label>OTP</label>
                            <div class="mb-3">
                                <input type="text" name="otp" class="form-control" placeholder="otp">
                                @error('otp')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="text-center">
                                <button type="submit"
                                    class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Verify</button>
                            </div>
                            <div class="text-center mt-4 font-weight-light">
                                Don't have an account? <a href="register.html" class="text-primary">Create</a>
                            </div>
                            <div class="text-center mt-4 font-weight-light">
                                <a href="{{ route('home') }}"
                                    class="text-info text-gradient h4 font-weight-bold text-uppercase">Back To Home</a>
                            </div>
                        </form>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var interval = setInterval(function() {
                $.ajax({
                    url: "{{ route('qrlogin') }}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status === true) {
                            clearInterval(interval);
                            console.log('Status is true. Interval stopped.');
                            window.location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }, 1000);
        });
    </script>
@endsection
