@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex  justify-content-between align-content-center">
                        <h4 class="card-title">{{ $role_type }}</h4>
                        <a href="{{ route('user.childUser.add', $role_type) }}" class="btn btn-primary">@lang('lang.Add :name', ['name' => $role_type])</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('lang.S.No.')</th>
                                    <th>@lang('lang.Name')</th>
                                    <th>@lang('lang.Image')</th>
                                    <th>@lang('lang.Mobile No.')</th>
                                    <th>@lang('lang.Status')</th>
                                    <th>@lang('lang.Role Name')</th>
                                    <th>@lang('lang.Dairy Name')</th>
                                    <th>@lang('lang.Dairy Address')</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($users->count() > 0)
                                    @foreach ($users as $key => $dairy)
                                        <tr>
                                            <td>{{ $key + 1 }} </td>
                                            <td>{{ $dairy->name ?? '' }}</td>
                                            <td>
                                                <img src="{{ asset($dairy->profile->image_path ?? 'default-image-path.jpg') }}"
                                                    alt="{{ $dairy->name ?? '' }}">

                                            </td>
                                            <td>{{ $dairy->country_code . ' ' . $dairy->mobile }}</td>
                                            <td>
                                                @if ($dairy->is_blocked)
                                                    <label class="badge badge-danger">@lang('lang.Blocked')</label>
                                            </td>
                                        @else
                                            <label class="badge badge-primary">@lang('lang.Active')</label></td>
                                    @endif
                                    <td><label class="badge badge-primary">{{ $dairy->role_name }}</label></td>
                                    <td>{{ $dairy->profile->dairy_name ?? '' }}</td>
                                    <td>{{ $dairy->profile->address ?? '' }}</td>
                                    <td>
                                        <button type="button" data-dairy-id="{{ $dairy->user_id }}"
                                            data-status={{ $dairy->is_blocked }} id="actionOnChildDairy"
                                            class="btn btn-primary btn-rounded btn-icon">
                                            <i class="fa fa-cogs" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">
                                        <div class="d-flex justify-content-center">
                                            No Records Found.
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
            'use strict';

            $.contextMenu({
                selector: '#actionOnChildDairy',
                className: 'contextmenu-customwidth',
                trigger: 'left',
                delay: 500,
                autoHide: true,
                callback: function(key, options) {
                    var dairy_id = $(this).data('dairy-id');
                    var status = $(this).data('status');
                    var m = "clicked: " + key;
                    if (key == 'edit') {
                        window.location.href = "{{ route('user.childUser.edit', ['dairy_id' => ':id']) }}"
                            .replace(':id', dairy_id);
                    } else if (key == 'view') {
                        window.location.href = "{{ route('user.childUser.view', ['dairy_id' => ':id']) }}"
                            .replace(':id', dairy_id);
                    } else if (key == 'status') {
                        swal.fire({
                            title: '@lang('lang.Are you sure?')',
                            text: (status == 0) ? "@lang('lang.You want to block user')" : "@lang('lang.You want to unblock user')",
                            icon: 'warning',
                            timer: 2000,
                            confirmButtonText: '@lang('lang.Yes')',
                            showCancelButton: true,
                            cancelButtonText: "@lang('lang.No')",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let _token = $('meta[name="csrf-token"]').attr('content');
                                $.ajax({
                                    url: "{{ route('user.childUser.status') }}",
                                    type: "POST",
                                    data: {
                                        _token: _token,
                                        dairy_id: dairy_id
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
                    "status": {
                        name: "@lang('lang.Status')",
                        icon: "fa-ban"
                    },
                    "quit": {
                        name: "@lang('lang.Quit')",
                        icon: "fa-sign-out"
                    }
                }
            });
        })(jQuery);
    </script>
@endsection
