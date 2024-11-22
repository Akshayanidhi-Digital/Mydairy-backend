@if (request()->routeIs('user.dashboard'))
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-12 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">@lang('lang.Welcome', ['name' => auth()->user()->name])
                    </h3>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row grid-margin">
        <div class="col-12 d-inline-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">{{ $title }}</h3>
            <a href="javascript:history.back()" class="btn btn-sm btn-light text-primary">Go Back</a>
        </div>
    </div>
@endif
