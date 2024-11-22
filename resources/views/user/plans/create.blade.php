@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="container text-center pt-2">
                        <p class="w-75 mx-auto mb-5">Choose a plan that suits you the best. If you are not fully satisfied,
                            we offer 30-day money-back guarantee no questions asked!!</p>
                        <div class="row pricing-table">
                            @foreach ($plans as $plan)
                                <div class="col-md-4 col-xl-4 grid-margin stretch-card pricing-card">
                                    <div class="card border-primary border pricing-card-body">
                                        <div class="text-center pricing-card-head">
                                            <h3>{{$plan->name}}</h3>
                                            <p>{{ Str::upper($plan->category) }}</p>
                                            <h1 class="font-weight-normal mb-4">&#8377; {{$plan->price}}</h1>
                                        </div>
                                        <ul class="list-unstyled plan-features">
                                            <li>{{$plan->duration.' '.$plan->duration_type}} validity</li>
                                            <li>{{$plan->description}}</li>
                                            <li>{{$plan->farmer_count }} Farmer User</li>
                                            <li>{{$plan->farmer_count }} Buyer User</li>
                                            <li>{{$plan->user_count }} Sub Dairy</li>
                                            <li>Free support for life time</li>
                                            <li>Free upgrade for one year</li>
                                        </ul>
                                        <div class="wrapper">
                                            <a href="{{route('user.plans.add',$plan->plan_id)}}" class="btn btn-outline-primary btn-block">@if(auth()->user()->plan_id == $plan->plan_id) Renew Plan @else Purchase Now @endif</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
