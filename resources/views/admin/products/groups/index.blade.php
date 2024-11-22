@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">{{ $title }}</h4>
                        <button type="button" class="btn btn-primary" onclick="addGroup()">@lang('lang.Add :name', ['name' => __('lang.Product Group')])</button>
                        <a href="">Add</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('lang.S.No.')</th>
                                    <th>@lang('lang.Name')</th>
                                    <th>@lang('lang.Status')</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($groups->total() > 0)
                                    @foreach ($groups as $key => $group)
                                        <tr>
                                            <td>{{ $groups->firstItem() + $key }}</td>
                                            <td>{{ $group->group }}</td>
                                            <td>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" data-style="quick"
                                                        @if ($group->trash == 0) checked @endif
                                                        data-on="@lang('lang.Enabled')" data-off="@lang('lang.Disabled')"
                                                        onchange="updateStatus({{ $group->id }})" data-toggle="toggle">
                                                </label>
                                            </td>
                                            <td>
                                                <button type="button"
                                                    onclick="updateGroup('{{ $group->group }}',{{ $group->id }})"
                                                    class="btn btn-md btn-outline-info btn-icon-text">
                                                    @lang('lang.Edit')
                                                    <i class="ti-file btn-icon-append"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($groups->total() > env('PER_PAGE_RECORDS'))
                                        <tr>
                                            <td colspan="12">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    {{ $groups->links('pagination::bootstrap-4') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <th colspan="4">
                                            <span class="w-100 text-center">No Rercords found</span>
                                        </th>
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
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/bootstrap-toggle/bootstrap-toggle.min.css') }}">
    <style>
        .toggle-group .btn-default {
            color: #020202;
            background-color: #e4e4e4;
            border-color: #8d8d8d;
        }

        .toggle.btn,
        .toggle-handle.btn {
            border-radius: 10px;
        }

        .toggle-handle.btn {
            background: #979797;
        }

        /* btn-default */
    </style>
@endsection
@section('scripts')
    <script src="{{ asset('assets/panel/vendors/bootstrap-toggle/bootstrap-toggle.min.js') }}"></script>
    <script>
        (function($) {
            addGroup = function() {
                swal.fire({
                    title: "@lang('lang.Add :name', ['name' => __('lang.Product Group')])", //'Add Product Group',
                    content: {
                        element: "input",
                        attributes: {
                            placeholder: "Enter Group name",
                            type: "text",
                        },
                    },
                    confirmButtonText: "@lang('lang.Yes')",
                    showCancelButton: true,
                    cancelButtonText: "@lang('lang.No')",
                }).then((result) => {
                    if (result.isConfirmed) {

                        var value = $(".swal-content__input").val();
                        let _token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('admin.groups.add') }}",
                            data: {
                                _token: _token,
                                group_name: value,
                            },
                            success: function(response) {
                                if (response.success) {
                                    iziToast.show({
                                        class: 'iziToast-color-green',
                                        message: response.message,
                                        position: "topRight",
                                        timeout: 2000,
                                        icon: 'ico-success',
                                        onClosing: function() {
                                            window.location.reload();
                                        },
                                        onClosed: function() {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    iziToast.error({
                                        message: response.message,
                                        position: "topRight",
                                        timeout: 2000,
                                    });
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                iziToast.error({
                                    message: "@lang('message.SOMETHING_WENT_WRONG')",
                                    position: "topRight",
                                    timeout: 2000,
                                });
                            }
                        });
                    }
                })
            }
            updateGroup = function(name, id) {
                console.log(name)
                swal.fire({
                    title: "@lang('lang.:name Update', ['name' => __('lang.Product Group')])", //'Update Group',
                    content: {
                        element: "input",
                        attributes: {
                            placeholder: "Enter Group name",
                            type: "text",
                            value: name,
                        },
                    },
                    confirmButtonText: '@lang('
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                lang.Yes ')',
                    showCancelButton: true,
                    cancelButtonText: "@lang('lang.No')",
                }).then((result) => {
                    if (result.isConfirmed) {

                        var value = $(".swal-content__input").val();
                        let _token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('admin.groups.update') }}",
                            data: {
                                _token: _token,
                                id: id,
                                group_name: value,
                            },
                            success: function(response) {
                                if (response.success) {
                                    iziToast.show({
                                        class: 'iziToast-color-green',
                                        message: response.message,
                                        position: "topRight",
                                        timeout: 2000,
                                        icon: 'ico-success',
                                        onClosing: function() {
                                            window.location.reload();
                                        },
                                        onClosed: function() {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    iziToast.error({
                                        message: response.message,
                                        position: "topRight",
                                        timeout: 2000,
                                    });
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                iziToast.error({
                                    message: "@lang('message.SOMETHING_WENT_WRONG')",
                                    position: "topRight",
                                    timeout: 2000,
                                });
                            }
                        });
                    }
                })
            }
            updateStatus = function(id) {
                let _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.groups.status') }}",
                    data: {
                        _token: _token,
                        id: id,
                    },
                    success: function(response) {
                        if (response.success) {
                            iziToast.show({
                                class: 'iziToast-color-green',
                                message: response.message,
                                position: "topRight",
                                timeout: 2000,
                                icon: 'ico-success',
                                onClosing: function() {
                                    window.location.reload();
                                },
                                onClosed: function() {
                                    window.location.reload();
                                }
                            });
                        } else {
                            iziToast.error({
                                message: response.message,
                                position: "topRight",
                                timeout: 2000,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        iziToast.error({
                            message: "@lang('message.SOMETHING_WENT_WRONG')",
                            position: "topRight",
                            timeout: 2000,
                        });
                    }
                });
            }
        })(jQuery);
    </script>
@endsection
