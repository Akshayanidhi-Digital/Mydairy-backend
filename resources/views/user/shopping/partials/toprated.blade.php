<div class="product-showcase">
    <h2 class="title">Top Rated</h2>
    <div class="showcase-wrapper has-scrollbar">
        <div class="showcase-container">
            @for ($i = 0; $i < 4; $i++)
                @include('user.shopping.comm.product-2')
            @endfor
        </div>
        <div class="showcase-container">
            @for ($i = 0; $i < 4; $i++)
                @include('user.shopping.comm.product-2')
            @endfor
        </div>
    </div>
</div>
