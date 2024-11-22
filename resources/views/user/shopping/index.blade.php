@extends('layouts.appecom')
@section('content')
    {{-- <div class="banner">
        <div class="container">
            <div class="owl-carousel owl-theme  slider-container has-scrollbar mainbanner">
                <div class="item slider-item">
                    <img src="{{ asset('assets/ecom/images/web_banner.png') }}" alt="women's latest fashion sale"
                        class="banner-img">
                    <div class="banner-content">
                        <p class="banner-subtitle">Trending item</p>
                        <h2 class="banner-title">Organic Dairy Products</h2>
                        <p class="banner-text text-light">
                            starting at $ <b>20</b>.00
                        </p>
                        <a href="#" class="banner-btn">Shop now</a>
                    </div>
                </div>
                <div class="item slider-item">
                    <img src="{{ asset('assets/ecom/images/web_banner1.png') }}" alt="modern sunglasses" class="banner-img">
                    <div class="banner-content">
                        <p class="banner-subtitle">Trending accessories</p>
                        <h2 class="banner-title">Modern sunglasses</h2>
                        <p class="banner-text">
                            starting at $ <b>15</b>.00
                        </p>
                        <a href="#" class="banner-btn">Shop now</a>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="product-container">
        <div class="container">
            {{-- <div class="sidebar  has-scrollbar" data-mobile-menu>
                <div class="sidebar-category">
                    <div class="sidebar-top">
                        <h2 class="sidebar-title">Category</h2>
                        <button class="sidebar-close-btn" data-mobile-menu-close-btn>
                            <ion-icon name="close-outline"></ion-icon>
                        </button>
                    </div>

                    <ul class="sidebar-menu-category-list">
                        @for ($i = 0; $i < 4; $i++)
                            @include('user.shopping.comm.category')
                        @endfor
                    </ul>

                </div>

                <div class="product-showcase">
                    <h3 class="showcase-heading">best sellers</h3>
                    <div class="showcase-wrapper">
                        <div class="showcase-container">
                            @for ($i = 0; $i < 4; $i++)
                                @include('user.shopping.comm.best-product')
                            @endfor
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="product-box">
                {{-- <div class="product-minimal">
                    @include('user.shopping.partials.new-Arrivals')
                    @include('user.shopping.partials.tranding')
                    @include('user.shopping.partials.toprated')
                </div> --}}
                {{-- @include('user.shopping.partials.deal-of-day') --}}
                <div class="product-main">
                    <h2 class="title">All Products</h2>
                    <div class="product-grid">
                        @foreach ($products as $product)
                            @include('user.shopping.comm.product', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/owl-carousel-2/owl.carousel.min.css') }}">
@endsection
@section('scripts')
    <script src="{{ asset('assets/panel/vendors/owl-carousel-2/owl.carousel.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';
            $.fn.andSelf = function() {
                return this.addBack.apply(this, arguments);
            }

            function addTocart(id) {
                let _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('user.shopping.cart.add') }}",
                    data: {
                        _token: _token,
                        product: id,
                    },
                    success: function(response) {
                        if (response.success) {
                            iziToast.success({
                                message: response.message,
                                position: "topRight",
                                timeout: 1500,
                            });
                            window.location.reload();
                        } else {
                            iziToast.error({
                                message: response.message,
                                position: "topRight",
                                timeout: 1500,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        iziToast.error({
                            message: xhr.responseJSON.message,
                            position: "topRight",
                            timeout: 1500,
                        });
                    }
                });
            }
            $('.addToCart').on('click', function() {
                var productId = $(this).data('product');
                console.log('Product ID:', productId);
                addTocart(productId);
            })



            // if ($('.toprated').length) {
            //     $('.toprated').owlCarousel({
            //         center: true,
            //         items: 1,
            //         loop: true,
            //         dots: false,
            //         autoplay: true,
            //         autoplayTimeout: 2500,
            //     });
            // }
            // if ($('.mainbanner').length) {
            //     $('.mainbanner').owlCarousel({
            //         center: true,
            //         items: 1,
            //         loop: true,
            //         dots: false,
            //         autoplay: true,
            //         autoplayTimeout: 2500,
            //     });
            // }
        })(jQuery);
    </script>
@endsection
