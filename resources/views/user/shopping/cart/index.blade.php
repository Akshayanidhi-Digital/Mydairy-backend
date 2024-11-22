@extends('layouts.app')
@section('content')
<div class="row justify-content-center m-0">
    <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-header bg-primary  p-3">
                <div class="card-header-flex">
                    <h5 class="text-white m-0">@lang('lang.Cart Items') <span id="product-count"></span></h5>
                    {{-- <button id="empty-cart" class="btn btn-danger mt-0 btn-sm d-none"><i
                                class="fa fa-trash mr-2"></i><span>@lang('lang.Empty Cart')</span></button> --}}
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table cart-table mb-0 d-none" id="cart-table">
                        <thead>
                            <tr>
                                <th>@lang('lang.Action')</th>
                                <th>@lang('lang.Product')</th>
                                <th>@lang('lang.Name')</th>
                                <th>@lang('lang.price')</th>
                                <th>@lang('lang.Quantity')</th>
                                <th class="text-right">@lang('lang.Total Amount')</th>
                            </tr>
                        </thead>
                        <tbody id="cart-body"></tbody>
                        <tfoot>
                            <tr>
                                <th>&nbsp;</th>
                                <th colSpan="2">&nbsp;</th>
                                <th>@lang('lang.Items in Cart')<span class="ml-2 mr-2">:</span><span class="text-danger"
                                        id="cart-total-qty"></span></th>
                                <th class="text-right">@lang('lang.Total Price')<span class="ml-2 mr-2">:</span><span
                                        class="text-danger" id="cart-total-amount"></span></th>
                                <th><a href="{{ route('user.shopping.checkout') }}"
                                        class="btn btn-outline-primary">Place Order</button></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="cart-empty d-none" id="cart-empty">
                    <i class="fa fa-shopping-cart text-primary"></i>
                    <p class="text-primary my-2">@lang('lang.Your Cart Is empty')</p>
                    <a href="{{ route('user.shopping.list') }}"
                        class="btn my-2 btn-primary btn-sm">@lang('lang.Continue Shopping')</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('styles')
<style>
    .card-header-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .product-img img {
        width: 50px;
        height: 50px;
        object-fit: cover;
    }

    .prdct-qty-container {
        display: flex;
        align-items: center;
    }

    .prdct-qty-btn {
        border: none;
        background: none;
    }

    .qty-input-box {
        width: 40px;
        text-align: center;
        margin: 0 5px;
    }

    .prdct-delete {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 4px;
        background-color: #fde6e7;
        color: #ff5b5c;
        font-size: 15px;
        transition: 0.3s;
    }

    .cart-empty {
        height: 50vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

    }

    .cart-empty i {
        font-size: 50px;
    }
</style>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productData = @json($carts);
        console.log(productData);
        // const productData = [{
        //         "id": 1,
        //         "image": "http://newdairy.test/assets/ecom/images/products/jacket-2.jpg",
        //         "name": "Coke - Diet, 355 Ml",
        //         "price": 120,
        //         "qty": 1,
        //     },
        //     {
        //         "id": 2,
        //         "image": "http://newdairy.test/assets/ecom/images/products/jacket-2.jpg",
        //         "name": "Pork - Hock And Feet Attached",
        //         "price": 150,
        //         "qty": 1,
        //     },
        //     {
        //         "id": 3,
        //         "image": "http://newdairy.test/assets/ecom/images/products/jacket-2.jpg",
        //         "name": "Veal - Jambu",
        //         "price": 135,
        //         "qty": 1,
        //     },
        //     {
        //         "id": 4,
        //         "image": "http://newdairy.test/assets/ecom/images/products/jacket-2.jpg",
        //         "name": "Almonds Ground Blanched",
        //         "price": 110,
        //         "qty": 1,
        //     },
        //     {
        //         "id": 5,
        //         "image": "http://newdairy.test/assets/ecom/images/products/jacket-2.jpg",
        //         "name": "Passion Fruit",
        //         "price": 80,
        //         "qty": 1,
        //     }
        // ];

        let products = [...productData];

        const renderCart = () => {
            const cartBody = document.getElementById('cart-body');
            const cartTable = document.getElementById('cart-table');
            const cartEmpty = document.getElementById('cart-empty');
            // const emptyCartBtn = document.getElementById('empty-cart');
            const productCount = document.getElementById('product-count');
            const cartTotalQtyElem = document.getElementById('cart-total-qty');
            const cartTotalAmountElem = document.getElementById('cart-total-amount');

            cartBody.innerHTML = '';

            if (products.length === 0) {
                cartTable.classList.add('d-none');
                cartEmpty.classList.remove('d-none');
                // emptyCartBtn.classList.add('d-none');
                productCount.textContent = '';
            } else {
                cartTable.classList.remove('d-none');
                cartEmpty.classList.add('d-none');
                // emptyCartBtn.classList.remove('d-none');
                productCount.textContent = `(${products.length})`;

                let cartTotalQty = 0;
                let cartTotalAmount = 0;

                products.forEach((data, index) => {
                    const {
                        id,
                        image,
                        image_url,
                        name,
                        price,
                        quantity,
                        total,
                        discount,
                        tax
                    } = data;
                    cartTotalQty += quantity;
                    cartTotalAmount += (quantity * (price - discount + tax));

                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td><button class="prdct-delete" onclick="removeFromCart(${index})"><i class="fa fa-trash"></i></button></td>
                    <td><div class="product-img"><img src="${image_url}" alt="" /></div></td>
                    <td><div class="product-name"><p>${name}</p></div></td>
                    <td>&#8377; ${(price-discount+tax).toFixed(2)}</td>
                    <td>
                        <div class="prdct-qty-container">
                            <button class="prdct-qty-btn" type="button" onclick="decreaseQuantity(${index})">
                                <i class="fa fa-minus"></i>
                            </button>
                            <input type="text" name="qty" class="qty-input-box" value="${quantity}" disabled />
                            <button class="prdct-qty-btn" type="button" onclick="increaseQuantity(${index})">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="text-right">&#8377; ${(quantity * (price-discount+tax)).toFixed(2)}</td>
                `;
                    cartBody.appendChild(row);
                });

                cartTotalQtyElem.textContent = cartTotalQty;
                cartTotalAmountElem.textContent = `₹  ${cartTotalAmount.toFixed(2)}`;
            }
        };

        const increaseQuantity = (i) => {
            products = products.map((data, o) => {
                if (i === o) {
                    const newQuantity = data.quantity + 1;
                    updateQuantityInDB(i, newQuantity);
                    return {
                        ...data,
                        quantity: newQuantity
                    };
                }
                return data;
            });
            renderCart();
        };

        const decreaseQuantity = (i) => {
            products = products.map((data, o) => {
                if (i === o) {
                    if (data.quantity > 1) {
                        const newQuantity = data.quantity - 1;
                        updateQuantityInDB(i, newQuantity);
                        return {
                            ...data,
                            quantity: newQuantity
                        };

                    }
                }
                return data;
            });
            renderCart();
        };

        function removeFromCart(index) {
            swal.fire({
                title: '@lang('
                lang.Are you sure ? ')',
                text : "@lang('lang.You want to remove this Product.')",
                icon: 'warning',
                confirmButtonText: '@lang('
                lang.Yes ')',
                showCancelButton: true,
                cancelButtonText: "@lang('lang.No')",
            }).then((result) => {
                if (result.isConfirmed) {
                    const product = products[index];
                    const _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('user.shopping.cart.remove') }}",
                        type: 'POST',
                        data: {
                            id: product.id,
                            _token: _token
                        },
                        success: function(response) {
                            if (response.success) {
                                products = products.filter((_, i) => i !== index);
                                renderCart();
                                Swal.fire('@lang('
                                    lang.Removed!')', '@lang('
                                    lang.The product has been removed from your cart.
                                    ')',
                                    'success');
                            } else {
                                Swal.fire('@lang('
                                    lang.Error!')', '@lang('
                                    lang.Failed to remove the product.
                                    ')',
                                    'error');
                            }
                        },
                        error: function() {
                            Swal.fire('@lang('
                                lang.Error!')', '@lang('
                                lang.Failed to remove the product.
                                ')', 'error');
                        }
                    });
                }
            });
        }
        const updateQuantityInDB = (index, quantity) => {
            const product = products[index];
            fetch("{{ route('user.shopping.cart.update')}}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        cart: product.id,
                        quantity: quantity
                    })
                }).then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Failed to update quantity');
                    }
                });
        };
        // const emptyCart = () => {
        //     if (confirm("Remove all items from your cart?")) {
        //         products = [];
        //         renderCart();
        //     }
        // };

        // Attach the emptyCart function to the button
        // document.getElementById('empty-cart').addEventListener('click', emptyCart);

        // Initial render
        renderCart();

        // Make functions globally accessible for inline onclick handlers
        window.increaseQuantity = increaseQuantity;
        window.decreaseQuantity = decreaseQuantity;
        window.removeFromCart = removeFromCart;
    });
</script>
@endsection