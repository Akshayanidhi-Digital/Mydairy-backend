@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex  justify-content-between align-content-center">
                    <h4 class="card-title">@lang('lang.Routes')</h4>
                    <a href="{{route('user.masters.routes.add')}}" class="btn btn-primary">@lang('lang.Add :name',['name'=>__('lang.Route')])</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>@lang('lang.S.No.')</th>
                                <th>@lang('lang.Route ID')</th>
                                <th>@lang('lang.Route Name')</th>
                                <th>@lang('lang.Transporter')</th>
                                <th>@lang('lang.Driver')</th>
                                <th>@lang('lang.Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($datas->count() > 0)
                            @foreach ($datas as $key => $data)
                            <tr>
                                <td>{{ $datas->firstItem() + $key }}</td>
                                <td>{{ $data->route_id ?? '' }}</td>
                                <td>{{ $data->route_name ?? '' }}</td>
                                <td>
                                    @if ($data->is_assigned)
                                    <span class="py-2">{{ $data->transporter->name ?? '' }} </span><br>
                                    <span class="py-2">{{ $data->transporter->transporter_name  ?? '' }}
                                    </span><br>
                                    <span class="py-2">
                                        {{ ($data->transporter->country_code ?? 'N/A') . ' ' . ($data->transporter->mobile ?? 'N/A') }}
                                    </span>

                                    @else
                                    NA
                                    @endif
                                </td>
                                <td>
                                    @if ($data->is_driver)
                                    {{ $data->driver->name }}
                                    @else
                                    NA
                                    @endif
                                </td>
                                <td>
                                    <button type="button" data-route-id="{{ $data->route_id }}"
                                        id="actionOnRoutes" class="btn btn-primary btn-rounded btn-icon">
                                        <i class="fa fa-cogs" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @if ($datas->total() > 10)
                            <tr>
                                <td colspan="6">
                                    <div class="d-flex justify-content-center align-items-center">
                                        {{ $datas->links('pagination::bootstrap-4') }}
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @else
                            <tr>
                                <td colspan="6">
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
        'use strict';

        $.contextMenu({
            selector: '#actionOnRoutes',
            className: 'contextmenu-customwidth',
            trigger: 'left',
            delay: 500,
            autoHide: true,
            callback: function(key, options) {
                var route_id = $(this).data('route-id');
                var status = $(this).data('status');
                if (key == 'edit') {
                    window.location.href =
                        "{{ route('user.masters.routes.edit', ['route_id' => ':id']) }}".replace(':id',
                            route_id);
                } else if (key == 'status') {
                    // swal.fire({
                    //     title: '@lang('lang.Are you sure?')',
                    //     text: (status == 0) ? "@lang('lang.You want to block user')" : "@lang('lang.You want to unblock user')",
                    //     icon: 'warning',
                    //     timer: 2000,
                    //     buttons: {
                    //         cancel: {
                    //             text: "@lang('lang.No')",
                    //             visible: true,
                    //             className: "btn btn-danger",
                    //             closeModal: true,
                    //         },
                    //         confirm: {
                    //             text: "@lang('lang.Yes')",
                    //             visible: true,
                    //             className: "btn btn-primary",
                    //         }
                    //     }
                    // }).then((result) => {
                    //                            if (result.isConfirmed) {

                    //         let _token = $('meta[name="csrf-token"]').attr('content');
                    //         $.ajax({
                    //             url: "{{ route('user.childUser.status') }}",
                    //             type: "POST",
                    //             data: {
                    //                 _token: _token,
                    //                 dairy_id: dairy_id
                    //             },
                    //             success: function(response) {
                    //                 if (response.status) {
                    //                     swal.fire({
                    //                         title: "Success",
                    //                         text: response.message,
                    //                         icon: "success",
                    //                         timer: 2000,
                    //                         showConfirmButton: false,
                    //                     }).then(() => {
                    //                         window.location.reload();
                    //                     });

                    //                 } else {
                    //                     swal.fire({
                    //                         title: "Error",
                    //                         text: response.message,
                    //                         icon: "info",
                    //                         timer: 2000,
                    //                         showConfirmButton: false,
                    //                     });
                    //                 }
                    //             },
                    //             error: function(xhr, status, error) {
                    //                 swal.fire({
                    //                     title: "@lang('lang.Oops!')",
                    //                     text: "@lang('message.SOMETHING_WENT_WRONG')",
                    //                     icon: "warning",
                    //                     timer: 2000,
                    //                     showConfirmButton: false,
                    //                 });
                    //             }
                    //         });
                    //     }
                    // });
                }
            },
            items: {
                "edit": {
                    name: "@lang('lang.Edit')",
                    icon: "fa-edit"
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