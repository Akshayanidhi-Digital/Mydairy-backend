@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    {{-- <h4 class="card-title"></h4> --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">@lang('lang.Buyers List')</h4>
                        <div class="d-flex justify-content-between align-items-center">

                            @if (request('trash') == 1)
                                <a href="{{ route('user.buyers.list') }}" class="btn btn-primary mr-2">
                                    @lang('lang.:name List', ['name' => __('lang.Buyer')])</a>
                                @if ($buyers->count() > 0)
                                    <a href="{{ route('user.buyers.deleteAll') }}" class="btn btn-primary">
                                        @lang('lang.Delete All')</a>
                                @endif
                            @else
                                <a href="{{ route('user.buyers.list') }}?trash=1" class="btn btn-primary mr-2">
                                    @lang('lang.Trash', ['name' => __('lang.Buyer')])</a>
                                <a href="{{ route('user.buyers.create') }}" class="btn btn-primary"> @lang('lang.Add :name', ['name' => __('lang.Buyer')])</a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('lang.S.No.')</th>
                                    <th>@lang('lang.Name')</th>
                                    <th>@lang('lang.Father Name')</th>
                                    <th>@lang('lang.Mobile')</th>
                                    <th>@lang('lang.Rate Type')</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($buyers->count() > 0)
                                    @foreach ($buyers as $key => $farmer)
                                        <tr>
                                            <td>
                                                {{ $key + 1 }}
                                            </td>
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
                                                <button type="button" data-farmer-id="{{ $farmer->buyer_id }}"
                                                    data-farmer-name="{{ $farmer->name }}" data-status={{ $farmer->trash }}
                                                    id="actionOnFarmer" class="btn btn-primary btn-rounded btn-icon">
                                                    <i class="fa fa-cogs" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($buyers->total() > 15)
                                        <tr>
                                            <td colspan="6">
                                                {{ $buyers->links('pagination::bootstrap-5') }}
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td colspan="6">
                                            <div class="w-100 text-center">
                                                No buyers records found.
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
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/jquery-contextmenu/jquery.contextMenu.min.css') }}">
    </link>
    <style>
        .contextmenu-customwidth {
            width: 100px !important;
            width: max-content !important;
            min-width: 80px !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('assets/panel/vendors/jquery-contextmenu/jquery.contextMenu.min.js') }}"></script>
    <script>
        (function($) {
            $.contextMenu({
                selector: '#actionOnFarmer',
                className: 'contextmenu-customwidth',
                trigger: 'left',
                delay: 500,
                autoHide: true,
                callback: function(key, options) {
                    var buyer_id = $(this).data('farmer-id');
                    var name = $(this).data('farmer-name');
                    var m = "clicked: " + key;
                    if (key == 'edit') {
                        window.location.href = "{{ route('user.buyers.edit', ['buyer_id' => ':id']) }}"
                            .replace(':id', buyer_id);
                    } else if (key == 'view') {
                        window.location.href = "{{ route('user.buyers.view', ['buyer_id' => ':id']) }}"
                            .replace(':id', buyer_id);
                    } else if (key == 'delete') {
                        @if (request('trash') == 1)
                            farmerSwalPDelete(name, buyer_id);
                        @else
                            farmerSwal(name, buyer_id);
                        @endif
                    }
                    @if (request('trash') == 1)
                        else if (key == 'restore') {
                            farmerSwalRestore(name, buyer_id);

                        }
                    @endif
                },
                items: {
                    "edit": {
                        name: "@lang('lang.Edit')",
                        icon: "fa-edit"
                    },
                    "view": {
                        name: "@lang('lang.View')",
                        icon: "fa-eye"
                    },
                    "delete": {
                        name: "@lang('lang.Delete')",
                        icon: "fa-ban"
                    },
                    @if (request('trash') == 1)
                        "restore": {
                            name: "@lang('lang.Restore')",
                            icon: "fa-repeat"
                        },
                    @endif
                }
            });
            @if (request('trash') == 1)
                farmerSwalRestore = function(name, farmerId) {
                    swal.fire({
                        title: '@lang('lang.Are you sure?')',
                        text: "@lang('lang.You want to Restore :name to the buyer list.')".replace(':name', name),
                        icon: 'warning',

                        // timer: 2000,
                        confirmButtonText: '@lang('lang.Yes')',
                        showCancelButton: true,
                        cancelButtonText: "@lang('lang.No')",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let _token = $('meta[name="csrf-token"]').attr('content');
                            $.ajax({
                                url: "{{ route('user.buyers.restore') }}",
                                type: "POST",
                                data: {
                                    _token: _token,
                                    buyer_id: farmerId
                                },
                                success: function(response) {
                                    if (response.status) {
                                        swal.fire({
                                            title: "Success",
                                            text: response.message,
                                            icon: "success",
                                            timer: 2000,
                                            showConfirmButton: false,
                                        }).then(() => {
                                            window.location.reload();
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
                    });
                }
                farmerSwalPDelete = function(name, farmerId) {
                    swal.fire({
                        title: '@lang('lang.Are you sure?')',
                        text: "@lang('lang.You want to permanent delete :name from the buyer list.')".replace(':name', name),
                        icon: 'warning',
                        // timer: 2000,
                        confirmButtonText: '@lang('lang.Yes')',
                        showCancelButton: true,
                        cancelButtonText: "@lang('lang.No')",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let _token = $('meta[name="csrf-token"]').attr('content');
                            $.ajax({
                                url: "{{ route('user.buyers.delete') }}",
                                type: "POST",
                                data: {
                                    _token: _token,
                                    buyer_id: farmerId
                                },
                                success: function(response) {
                                    if (response.status) {
                                        swal.fire({
                                            title: "Success",
                                            text: response.message,
                                            icon: "success",
                                            timer: 2000,
                                            showConfirmButton: false,
                                        }).then(() => {
                                            window.location.reload();
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
                    });
                }
            @else
                farmerSwal = function(name, buyerId) {
                    swal.fire({
                        title: '@lang('lang.Are you sure?')',
                        text: "@lang('lang.You want to delete :name from the buyers list.')".replace(':name', name),
                        icon: 'warning',
                        timer: 2000,
                        confirmButtonText: '@lang('lang.Yes')',
                        showCancelButton: true,
                        cancelButtonText: "@lang('lang.No')",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let _token = $('meta[name="csrf-token"]').attr('content');

                            $.ajax({
                                url: "{{ route('user.buyers.delete') }}",
                                type: "POST",
                                data: {
                                    _token: _token,
                                    buyer_id: buyerId
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
                                        window.location.reload();
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
            @endif
        })(jQuery);
    </script>
@endsection
