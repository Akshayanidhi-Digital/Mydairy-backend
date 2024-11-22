@extends('main.layout.app')

@section('content')
    <section class="section page-title">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 m-auto">
                    <!-- Page Title -->
                    <h1>Contact Us</h1>
                    <!-- Page Description -->
                    <p>Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Vivamus magna justo, lacinia
                        eget consectetur sed, convallis at tellus. Cras ultricies ligula sed magna dictum porta.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="address">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center">
                    <div class="block">
                        <div class="address-block text-center mb-5">
                            <div class="icon">
                                <i class="ti-mobile"></i>
                            </div>
                            <div class="details">
                                <h3>(+91) 759 735 5063 (IN)</h3>
                                {{-- <h3>(+91) 759 735 5063 (IN)</h3> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <div class="block">
                        <div class="address-block text-center">
                            <div class="icon">
                                <i class="ti-map-alt"></i>
                            </div>
                            <div class="details">
                                <h3>11, Panchsheel Enclave, J.L.N. Marg,</h3>
                                <h3>Near Hotel Clark’s Amer,</h3>
                                <h3>Jaipur -302018, RJ, Bharat</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-form section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-5 text-center">Drop us a mail</h2>
                </div>
                <div class="col-12">
                    <form action="">
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-2">
                                <input class="form-control main" type="text" placeholder="Name" required>
                            </div>
                            <!-- Email -->
                            <div class="col-md-6 mb-2">
                                <input class="form-control main" type="email" placeholder="Your Email Address" required>
                            </div>
                            <!-- subject -->
                            <div class="col-md-12 mb-2">
                                <input class="form-control main" type="text" placeholder="Subject" required>
                            </div>
                            <!-- Message -->
                            <div class="col-md-12 mb-2">
                                <textarea class="form-control main" name="message" rows="10" placeholder="Your Message"></textarea>
                            </div>
                            <!-- Submit Button -->
                            <div class="col-12 text-right">
                                <button class="btn btn-main-md">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
