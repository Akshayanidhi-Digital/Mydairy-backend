@extends('layouts.app')
@section('content')
    <diw class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between my-2 align-content-center">
                        <h4 class="card-title">{{ $title ?? '' }}</h4>
                        <a href="{{ route('admin.apphelp.create') }}" class="btn btn-primary">Add Help</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Help Name</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Play</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($datas->total() <= 0)
                                    <tr>

                                        <td colspan="5">
                                            <div class="d-flex justify-content-center">No Helps Data Found</div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($datas as $key => $data)
                                        <tr>
                                            <td>{{ $datas->firstItem() + $key }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>
                                                @if ($data->trash)
                                                    <div class="badge badge-danger">Inactive</div>
                                                @else
                                                    <div class="badge badge-primary">Active</div>
                                                @endif
                                            </td>
                                            <td><img src="{{ asset($data->image_path) }}" alt="{{ $data->name }}"></td>
                                            <td>
                                                <a href="{{ $data->url }}" target="_blank"
                                                    class="btn btn-success">Play</a>
                                            </td>
                                            <td>
                                                <button type="button" data-help-id="{{ $data->id }}"
                                                    data-status={{ $data->trash }} id="actionOnHelp"
                                                    class="btn btn-primary btn-rounded btn-icon">
                                                    <i class="fa fa-cogs" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </diw>
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
                selector: '#actionOnHelp',
                className: 'contextmenu-customwidth',
                trigger: 'left',
                delay: 500,
                autoHide: true,
                callback: function(key, options) {
                    var help_id = $(this).data('help-id');
                    var status = $(this).data('status');
                    var m = "clicked: " + key;
                    switch (key) {
                        case "edit":
                            window.location.href = "{{ route('admin.apphelp.edit', ['id' => ':id']) }}"
                                .replace(':id', help_id);
                            break;
                        case "update":
                            updateStatus(help_id, status);
                            break;
                        case "delete":
                            deleteHelp(help_id);
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
                    "update": {
                        name: "@lang('lang.update')",
                        icon: "fa-eye"
                    },
                    "delete": {
                        name: "@lang('lang.Delete')",
                        icon: "fa-ban"
                    },
                }
            });
            updateStatus = function(id, status) {
                swal.fire({
                    title: 'Are you sure?',
                    text: (status) ? "You want to display this help in application." :
                        "You want to block access of this help form application.",
                    icon: 'warning',
                    buttons: {
                        cancel: {
                            text: "Cancel",
                            visible: true,
                            className: "btn btn-danger",
                            closeModal: true,
                        },
                        confirm: {
                            text: "Confirm",
                            visible: true,
                            className: "btn btn-primary",
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        let _token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: "{{ route('admin.apphelp.status') }}",
                            type: "POST",
                            data: {
                                _token: _token,
                                id: id
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
                                    title: "Oops!",
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
            deleteHelp = function(id) {
                swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete this help.",
                    icon: 'warning',
                    buttons: {
                        cancel: {
                            text: "Cancel",
                            visible: true,
                            className: "btn btn-danger",
                            closeModal: true,
                        },
                        confirm: {
                            text: "Confirm",
                            visible: true,
                            className: "btn btn-primary",
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        let _token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: "{{ route('admin.apphelp.delete') }}",
                            type: "POST",
                            data: {
                                _token: _token,
                                id: id
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
                                    title: "Oops!",
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
