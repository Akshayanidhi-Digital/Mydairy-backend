<div class="showcase">

    <div class="showcase-banner">

        <img src="{{ asset($product->image_base . '/' . $product->image) }}" alt="Mens Winter Leathers Jackets"
            width="300" class="product-img default">
        <img src="{{ asset($product->image_base . '/' . $product->image) }}" alt="Mens Winter Leathers Jackets"
            width="300" class="product-img hover">

        {{-- <p class="showcase-badge">15%</p> --}}

        <div class="showcase-actions">
            {{--
            <button class="btn-action">
                <ion-icon name="heart-outline"></ion-icon>
            </button> --}}

            <button class="btn-action">
                <ion-icon name="eye-outline"></ion-icon>
            </button>
            {{--
            <button class="btn-action">
                <ion-icon name="repeat-outline"></ion-icon>
            </button> --}}

            <button class="btn-action addToCart" data-product={{ $product->id }}>
                <ion-icon name="bag-add-outline"></ion-icon>
            </button>

        </div>

    </div>

    <div class="showcase-content">

        <a href="#" class="showcase-category">{{ $product->group_name }}</a>

        <a href="#">
            <h3 class="showcase-title">{{ $product->name }}</h3>
        </a>

        <div class="showcase-rating">
            <ion-icon name="star"></ion-icon>
            <ion-icon name="star"></ion-icon>
            <ion-icon name="star"></ion-icon>
            <ion-icon name="star-outline"></ion-icon>
            <ion-icon name="star-outline"></ion-icon>
        </div>
        @php
            $basePrice = $product->price + ($product->price * $product->tax) / 100;
        @endphp
        <div class="price-box">
            <p class="price">&#8377; {{ number_format($basePrice - 0, 2) }}
            </p>
            <del>&#8377; {{ number_format($basePrice - 0, 2) }}</del>
        </div>

    </div>

</div>
