@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
@endsection
@section('scripts')
    {{-- <script src="{{ asset('assets/panel/vendors/typeahead.js/typeahead.bundle.min.js') }}"></script> --}}
    <script src="{{ asset('assets/panel/vendors/select2/select2.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';
            if ($(".select-plan-type").length) {
                $(".select-plan-type").select2();
            }

        })(jQuery);
    </script>
    <script>
        $('.class').on('click',()=>{
            var as = this.att
        });
    </script>
@endsection
@section('content')
    <diw class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title ?? '' }}</h4>
                    <form action="{{route('admin.plans.store')}}" method="post" >
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="plan_name">Plan Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="plan_name" value="{{old('plan_name')}}">
                                @error('plan_name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="category">Category</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="category">
                                    <option value="">Select</option>
                                    <option @if (old('category') == 'single')
                                    selected
                                @endif  value="single">Single</option>
                                    <option @if (old('category') == 'multiple')
                                    selected
                                @endif  value="multiple">Multiple</option>
                                </select>
                                @error('category')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="user_allowed">Allowed User</label>
                            <div class="col-sm-9">
                                <input type="number" min="0" class="form-control" name="user_allowed" value="{{old('user_allowed')}}">
                                @error('user_allowed')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="farmer_count">Allowed Farmers</label>
                            <div class="col-sm-9">
                                <input type="number" min="0" class="form-control" name="farmer_count" value="{{old('farmer_count')}}">
                                @error('farmer_count')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="price">Price</label>
                            <div class="col-sm-9">
                                <input type="number" min="0" step="0.01" class="form-control" name="price" value="{{old('price')}}">
                                @error('price')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="duration">Duration</label>
                            <div class="col-sm-9">
                                <input type="number" min="0"  class="form-control" name="duration" value="{{old('duration')}}">
                                @error('duration')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="duration_type">Duration Type</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="duration_type">
                                    <option value="">Select</option>
                                    <option @if (old('duration_type') == 'day')
                                    selected
                                @endif  value="day">Days</option>
                                <option @if (old('duration_type') == 'month')
                                    selected
                                @endif  value="month">Month</option>
                                <option @if (old('duration_type') == 'year')
                                    selected
                                @endif  value="year">Year</option>
                                </select>
                                @error('duration_type')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{old('description')}}</textarea>
                            @error('description')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                          </div>
                        <button class="btn btn-sm form-control btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </diw>
@endsection
