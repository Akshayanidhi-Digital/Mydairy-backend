@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h6 class="font-weight-normal mb-4 mt-2">@lang('lang.All :type milk Rate chart listed here.', ['type' => __('lang.Buy')])</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-cow text-primary icon-lg"></span>
                        <div class="ms-3 text-center">
                            <h6 class="text-primary">@lang('lang.:name Milk Rate Chart', ['name' => __('lang.Cow')])</h6>
                            <a href="{{ route('user.rateCharts.view', ['type' => 'buy', 'name' => 'cow']) }}"
                                class="mt-2 d-flex flex-row align-items-center justify-content-center">
                                <p class="mdi mdi-hand-pointing-right icon-md mr-2"></p>
                                <p>@lang('lang.Here')</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-cow text-primary icon-lg"></span>
                        <div class="ms-3 text-center">
                            <h6 class="text-primary">@lang('lang.:name Milk Rate Chart', ['name' => __('lang.Buffalo')])</h6>
                            <a href="{{ route('user.rateCharts.view', ['type' => 'buy', 'name' => 'buffalo']) }}"
                                class="mt-2 d-flex flex-row align-items-center justify-content-center">
                                <p class="mdi mdi-hand-pointing-right icon-md mr-2"></p>
                                <p>@lang('lang.Here')</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-cow text-primary icon-lg"></span>
                        <div class="ms-3 text-center">
                            <h6 class="text-primary">@lang('lang.:name Milk Rate Chart', ['name' => __('lang.Mix')])</h6>
                            <a href="{{ route('user.rateCharts.view', ['type' => 'buy', 'name' => 'mix']) }}"
                                class="mt-2 d-flex flex-row align-items-center justify-content-center">
                                <p class="mdi mdi-hand-pointing-right icon-md mr-2"></p>
                                <p>@lang('lang.Here')</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-8 mb-4 mb-xl-2">
            <h6 class="font-weight-normal mb-0">@lang('lang.All :type milk Rate chart listed here.', ['type' => __('lang.sale')])</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-cow text-primary icon-lg"></span>
                        <div class="ms-3 text-center">
                            <h6 class="text-primary">@lang('lang.:name Milk Rate Chart', ['name' => __('lang.Cow')])</h6>
                            <a href="{{ route('user.rateCharts.view', ['type' => 'sell', 'name' => 'cow']) }}"
                                class="mt-2 d-flex flex-row align-items-center justify-content-center">
                                <p class="mdi mdi-hand-pointing-right icon-md mr-2"></p>
                                <p>@lang('lang.Here')</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-cow text-primary icon-lg"></span>
                        <div class="ms-3 text-center">
                            <h6 class="text-primary">@lang('lang.:name Milk Rate Chart', ['name' => __('lang.Buffalo')])</h6>
                            <a href="{{ route('user.rateCharts.view', ['type' => 'sell', 'name' => 'buffalo']) }}"
                                class="mt-2 d-flex flex-row align-items-center justify-content-center">
                                <p class="mdi mdi-hand-pointing-right icon-md mr-2"></p>
                                <p>@lang('lang.Here')</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-xl-4 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-cow text-primary icon-lg"></span>
                        <div class="ms-3 text-center">
                            <h6 class="text-primary">@lang('lang.:name Milk Rate Chart', ['name' => __('lang.Mix')])</h6>
                            <a href="{{ route('user.rateCharts.view', ['type' => 'sell', 'name' => 'mix']) }}"
                                class="mt-2 d-flex flex-row align-items-center justify-content-center">
                                <p class="mdi mdi-hand-pointing-right icon-md mr-2"></p>
                                <p>@lang('lang.Here')</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-6 col-xl-6 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-cloud-download text-primary icon-lg"></span>
                        <div class="ms-3 text-center">
                            <h6 class="text-primary">@lang('lang.Download Sample Rate chart')</h6>
                            <a href="{{ route('user.rateCharts.sampleDownload') }}"
                                class="mt-2 d-flex flex-row align-items-center justify-content-center">
                                <p class="mdi mdi-hand-pointing-right icon-md mr-2"></p>
                                <p>@lang('lang.Here')</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-xl-6 stretch-card">
            <div class="card d-flex align-items-center card-border grid-margin">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <span class="mdi mdi-cloud-upload text-primary icon-lg"></span>
                        <div class="ms-3 text-center">
                            <h6 class="text-primary">@lang('lang.Upload Rate charts')</h6>
                            <a href="{{ route('user.rateCharts.upload') }}"
                                class="mt-2 d-flex flex-row align-items-center justify-content-center">
                                <p class="mdi mdi-hand-pointing-right icon-md mr-2"></p>
                                <p>@lang('lang.Here')</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <style>
        .card-border {
            border: 1px solid #007bff;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/mdi/css/materialdesignicons.min.css') }}">
@endsection
@section('scripts')
@endsection
