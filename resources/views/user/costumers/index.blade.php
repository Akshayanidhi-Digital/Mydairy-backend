@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('lang.Total Farmers')</h4>
                    <div class="d-flex justify-content-between">
                        <p class="text-muted">@lang('lang.Active'): {{ CostumerData('farmer', 0) }}</p>
                        <p class="text-muted">@lang('lang.All'): {{ CostumerData('farmer', 0) + CostumerData('farmer', 1) }}
                        </p>
                    </div>
                    <div class="progress progress-md">
                        <div class="progress-bar bg-info"
                            style="width: @if (CostumerData('farmer', 0) > 0) {{ (CostumerData('farmer', 0) / (CostumerData('farmer', 0) + CostumerData('farmer', 1))) * 100 }}% @else 0% @endif"
                            role="progressbar"
                            aria-valuenow=" @if (CostumerData('farmer', 0) > 0) {{ (CostumerData('farmer', 0) / (CostumerData('farmer', 0) + CostumerData('farmer', 1))) * 100 }} @else 0 @endif"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('lang.Total Buyers')</h4>
                    <div class="d-flex justify-content-between">
                        <p class="text-muted">@lang('lang.Active'): {{ CostumerData('buyer', 0) }}</p>
                        <p class="text-muted">@lang('lang.All'): {{ CostumerData('buyer', 0) + CostumerData('buyer', 1) }}
                        </p>
                    </div>
                    <div class="progress progress-md">
                        <div class="progress-bar bg-info"
                            style="width: @if (CostumerData('buyer', 0) > 0) {{ (CostumerData('buyer', 0) / (CostumerData('buyer', 0) + CostumerData('buyer', 1))) * 100 }}% @else 0 @endif"
                            role="progressbar"
                            aria-valuenow=" @if (CostumerData('buyer', 0) > 0) {{ (CostumerData('buyer', 0) / (CostumerData('buyer', 0) + CostumerData('buyer', 1))) * 100 }} @else 0 @endif"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-12 col-sm-6 col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Total Income</h4>
                    <div class="d-flex justify-content-between">
                        <p class="text-muted">@lang('lang.Active'): {{ CostumerData('farmer', 0) }}</p>
                        <p class="text-muted">@lang('lang.All'): {{ CostumerData('farmer', 0) + CostumerData('farmer', 1) }}</p>
                    </div>
                    <div class="progress progress-md">
                        <div class="progress-bar bg-info"
                            style="width: @if (CostumerData('farmer', 0) > 0) {{ (CostumerData('farmer', 0) / (CostumerData('farmer', 0) + CostumerData('farmer', 1))) * 100 }}% @else 0% @endif"
                            role="progressbar"
                            aria-valuenow=" @if (CostumerData('farmer', 0) > 0)  {{ (CostumerData('farmer', 0) / (CostumerData('farmer', 0) + CostumerData('farmer', 1))) * 100 }} @else 0 @endif"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-12 col-sm-6 col-md-6 col-xl-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Total Income</h4>
                    <div class="d-flex justify-content-between">
                        <p class="text-muted">@lang('lang.Active'): {{ CostumerData('farmer', 0) }}</p>
                        <p class="text-muted">@lang('lang.All'): {{ CostumerData('farmer', 0) + CostumerData('farmer', 1) }}</p>
                    </div>
                    <div class="progress progress-md">
                        <div class="progress-bar bg-info"
                            style="width: @if (CostumerData('buyer', 0) > 0)  {{ (CostumerData('buyer', 0) / (CostumerData('buyer', 0) + CostumerData('buyer', 1))) * 100 }}% @else 0 @endif"
                            role="progressbar"
                            aria-valuenow=" @if (CostumerData('buyer', 0) > 0) {{ (CostumerData('buyer', 0) / (CostumerData('buyer', 0) + CostumerData('buyer', 1))) * 100 }} @else 0 @endif"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('lang.Farmers List')</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>@lang('lang.Name')</th>
                                    <th>@lang('lang.Father Name')</th>
                                    <th>@lang('lang.Mobile')</th>
                                    <th>@lang('lang.Rate Type')</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($farmers->count() > 0)
                                    @foreach ($farmers as $farmer)
                                        <tr>
                                            <td>{{ $farmer->farmer_id }}</td>
                                            <td>{{ $farmer->name }}</td>
                                            <td>{{ $farmer->father_name }}</td>
                                            <td>{{ $farmer->country_code . ' ' . $farmer->mobile }}</td>
                                            <td>
                                                @if ($farmer->is_fixed_rate)
                                                    @lang('lang.Fixed Rate')
                                                @else
                                                    @lang('lang.Default Rate')
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="{{ route('user.farmers.view', $farmer->farmer_id) }}"
                                                        type="button" class="btn btn-primary">@lang('lang.View')</a>
                                                    <a href="{{ route('user.farmers.edit', $farmer->farmer_id) }}"
                                                        class="btn btn-info">@lang('lang.Edit')</a>
                                                    <button type="button"
                                                        onclick="farmerSwal('{{ $farmer->name }}',{{ $farmer->farmer_id }})"
                                                        class="btn btn-danger">@lang('lang.Delete')</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">
                                            <div class="w-100 text-center">
                                                No farmers records found.
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        (function($) {
            farmerSwal = function(name, farmerId) {
                swal.fire({
                    title: '@lang('lang.Are you sure?')',
                    text: "@lang('lang.You want to delete :name from the farmer list.')".replace(':name', name),
                    icon: 'warning',
                    timer: 2000,
                    confirmButtonText: '@lang('lang.Yes')',
                    showCancelButton: true,
                    cancelButtonText: "@lang('lang.No')",
                }).then((result) => {
                    if (result.isConfirmed) {

                        let _token = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            url: "{{ route('user.farmers.delete') }}",
                            type: "POST",
                            data: {
                                _token: _token,
                                farmer_id: farmerId
                            },
                            success: function(response) {
                                if (response.status) {
                                    swal.fire({
                                        title: "Success",
                                        text: response.message,
                                        icon: "success",
                                        timer: 2000,
                                        showConfirmButton: false,
                                    });
                                } else {
                                    swal.fire({
                                        title: "Error",
                                        text: response.message,
                                        icon: "info",
                                        timer: 2000,
                                        showConfirmButton: false,
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                swal.fire({
                                    title: "@lang('lang.Oops!')",
                                    text: "@lang('message.SOMETHING_WENT_WRONG')",
                                    icon: "warning",
                                    timer: 2000,
                                    showConfirmButton: false,
                                });
                            }
                        });
                    }
                })
            }
        })(jQuery);
    </script>
@endsection
