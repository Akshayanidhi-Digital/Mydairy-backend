@extends('layouts.transport')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('lang.S.No.')</th>
                                    <th>@lang('lang.Name')</th>
                                    <th>@lang('lang.Father Name')</th>
                                    <th>@lang('lang.Email ID.')</th>
                                    <th>@lang('lang.Mobile No.')</th>
                                    <th>@lang('lang.Status')</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($datas->count() > 0)
                                    @foreach ($datas as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->father_name }}</td>
                                            <td>{{ $data->email ?? 'NA' }}</td>
                                            <td>{{ $data->country_code . ' ' . $data->mobile }}</td>
                                            <td>
                                                @if ($data->is_blocked)
                                                    <span class="badge badge-danger">@lang('lang.Blocked')</span>
                                                @else
                                                    <span class="badge badge-primary">@lang('lang.Active')</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" data-data-id="{{ $data->driver_id }}" id="actionBtn"
                                                    class="btn btn-primary btn-rounded btn-icon">
                                                    <i class="fa fa-cogs" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($datas->total() > env('PER_PAGE_RECORDS'))
                                        <tr>
                                            <td colspan="5">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    {{ $datas->links('pagination::bootstrap-4') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td colspan="5">
                                            <div class="d-flex justify-content-center">
                                                @lang('lang.No Records Found')
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
                selector: '#actionBtn',
                className: 'contextmenu-customwidth',
                trigger: 'left',
                delay: 500,
                autoHide: true,
                callback: function(key, options) {
                    var dataId = $(this).data('data-id');
                    switch (key) {
                        case "edit":
                            window.location.href = "{{ route('transport.driver.edit', ['id' => ':id']) }}"
                                .replace(':id', dataId);
                            break;
                        case 'update':
                            updateDriver(dataId);
                            break;
                        case 'delete':
                            deleteDriver(dataId);
                            break;
                        default:
                            break;
                    }
                },
                items: {
                    "edit": {
                        name: "@lang('lang.Edit')",
                        icon: "fa-edit"
                    },
                    // "view": {
                    //     name: "@lang('lang.View')",
                    //     icon: "fa-eye"
                    // },
                    'update': {
                        name: "Status Update",
                        icon: "fa-ban"
                    },
                    "delete": {
                        name: "@lang('lang.Delete')",
                        icon: "fa-trash"
                    },
                }
            });

            function updateDriver(dataId) {
                swal.fire({
                    title: '@lang('lang.Are you sure?')',
                    text: 'You want to update this driver status.',
                    icon: 'warning',
                    timer: 2000,
                    confirmButtonText: '@lang('lang.Yes')',
                    showCancelButton: true,
                    cancelButtonText: "@lang('lang.No')",
                }).then((result) => {
                    if (result.isConfirmed) {

                        let _token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: "{{ route('transport.driver.updateStatus') }}",
                            type: "POST",
                            data: {
                                _token: _token,
                                driver_id: dataId
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

            function deleteDriver(dataId) {
                swal.fire({
                    title: '@lang('lang.Are you sure?')',
                    text: 'You want to delete this driver.',
                    icon: 'warning',
                    timer: 2000,
                    confirmButtonText: '@lang('lang.Yes')',
                    showCancelButton: true,
                    cancelButtonText: "@lang('lang.No')",
                }).then((result) => {
                    if (result.isConfirmed) {
                        let _token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: "{{ route('transport.driver.delete') }}",
                            type: "POST",
                            data: {
                                _token: _token,
                                driver_id: dataId
                            },
                            success: function(response) {
                                if (response.success) {
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
        })(jQuery);
    </script>
@endsection
