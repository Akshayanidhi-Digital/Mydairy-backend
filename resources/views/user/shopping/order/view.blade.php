@extends('layouts.app')
@section('content')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <div class="row justify-content-between mb-4">
                    <div class="order-tracking completed">
                        <span class="is-complete"></span>
                        <p>Ordered<br><span>{{ customDate($order->created_at) }}</span></p>
                    </div>
                    <div class="order-tracking">
                        <span class="is-complete"></span>
                        <p>Preparing<br></p>
                    </div>
                    <div class="order-tracking">
                        <span class="is-complete"></span>
                        <p>Out For delivery<br></p>
                    </div>
                    <div class="order-tracking">
                        <span class="is-complete"></span>
                        <p>Delivered<br></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="w-100 h-100 d-flex justify-content-center align-items-center">
                            <img class="w-100" src="{{ asset($order->order_items->image) }}"
                                alt="{{ $order->order_items->name }}">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <ul class="list-arrow">
                            <li>Price : &#8377; {{ $order->order_items->price }}</li>
                            <li>Tax : &#8377; {{ $order->order_items->tax }}</li>
                            <li>Discount : &#8377; {{ $order->order_items->discount }}</li>
                            <li>Quantity : {{ $order->order_items->quantity }}</li>
                            <li>Total : &#8377; {{ $order->order_items->total }}</li>
                        </ul>
                    </div>
                </div>
                <p class="my-3">Other Items in this order</p>
                @foreach ($others as $od)
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="w-100 h-100 d-flex justify-content-center align-items-center">
                                <img class="w-100" src="{{ asset($od->order_items->image) }}"
                                    alt="{{ $od->order_items->name }}">
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <ul class="list-arrow">
                                <li>Price : &#8377; {{ $od->order_items->price }}</li>
                                <li>Tax : &#8377; {{ $od->order_items->tax }}</li>
                                <li>Discount : &#8377; {{ $od->order_items->discount }}</li>
                                <li>Quantity : {{ $od->order_items->quantity }}</li>
                                <li>Total : &#8377; {{ $od->order_items->total }}</li>
                            </ul>
                        </div>
                    </div>
                @endforeach
                <div class="w-100 my-3">
                    <a href="{{ route('user.shopping.order.print', $order->_id) }}" class="btn btn-outline-primary float-right">Print</a>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('styles')
    <style>
        .order-tracking {
            text-align: center;
            width: 25%;
            position: relative;
            display: block;
        }

        .order-tracking .is-complete {
            display: block;
            position: relative;
            border-radius: 50%;
            height: 30px;
            width: 30px;
            border: 0px solid #AFAFAF;
            background-color: #AFAFAF;
            margin: 0 auto;
            transition: background 0.25s linear;
            -webkit-transition: background 0.25s linear;
            z-index: 2;
        }

        .order-tracking .is-complete:after {
            display: block;
            position: absolute;
            content: '';
            height: 14px;
            width: 7px;
            top: -2px;
            bottom: 0;
            left: 5px;
            margin: auto 0;
            border: 0px solid #AFAFAF;
            border-width: 0px 2px 2px 0;
            transform: rotate(45deg);
            opacity: 0;
        }

        .order-tracking.completed .is-complete {
            border-color: #0066B7;
            border-width: 0px;
            background-color: #0066B7;
        }

        .order-tracking.completed .is-complete:after {
            border-color: #fff;
            border-width: 0px 3px 3px 0;
            width: 7px;
            left: 11px;
            opacity: 1;
        }

        .order-tracking p {
            color: #A4A4A4;
            font-size: 16px;
            margin-top: 8px;
            margin-bottom: 0;
            line-height: 20px;
        }

        .order-tracking p span {
            font-size: 14px;
        }

        .order-tracking.completed p {
            color: #000;
        }

        .order-tracking::before {
            content: '';
            display: block;
            height: 3px;
            width: calc(100% - 40px);
            background-color: #AFAFAF;
            top: 13px;
            position: absolute;
            left: calc(-50% + 20px);
            z-index: 0;
        }

        .order-tracking:first-child:before {
            display: none;
        }

        .order-tracking.completed:before {
            background-color: #0066B7;
        }
    </style>
@endsection
