@extends('layouts.transport')
@section('content')
    <div class="row">
        <div class="col-sm-4">
            <div class="card card-border grid-margin">
                <div class="card-body">
                    <p class="mb-4">@lang('lang.Total :name', ['name' => __('lang.Dairy')])</p>
                    <p class="fs-30 mb-2">{{ $total_dairy }}</p>
                    {{-- <p>10.00% (30 days)</p> --}}
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-border grid-margin">
                <div class="card-body">
                    <p class="mb-4">@lang('lang.Total :name', ['name' => __('lang.Route')])</p>
                    <p class="fs-30 mb-2">{{ $total_route }}</p>
                    {{-- <p>10.00% (30 days)</p> --}}
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-border grid-margin">
                <div class="card-body">
                    <p class="mb-4">@lang('lang.Total :name', ['name' => __('lang.Driver')])</p>
                    <p class="fs-30 mb-2">{{ $total_driver }}</p>
                    {{-- <p>10.00% (30 days)</p> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 ">
            <div class="card grid-margin">
                <div class="card-body">
                    <p class="card-title mb-2">@lang('lang.Active :name List',['name'=>__('lang.Route')])</p>
                    @include('transport.routes.table',['datas'=>$routes_list])
                </div>
            </div>
        </div>
    </div>
@endsection
