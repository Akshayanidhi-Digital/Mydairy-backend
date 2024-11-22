@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <ul class="bullet-line-list ">
                        @foreach ($datas as $data)
                            <li @if ($data->is_marked) class="old" @endif>
                                <h6>{{ getNotificationTitle($data->message_type) }}</h6>
                                <p>{{ $data->message }}</p>
                                <p class="text-muted ">
                                    <i class="ti-time"></i>
                                    {{ getNotificationTime($data->created_at) }}
                                </p>
                                <div class="mb-4">
                                    @if (!$data->is_marked && $data->message_type != 2)
                                        <button type="button" data-id="{{ $data->id }}"
                                            class="btn markRead btn-primary btn-sm">Mark as Read</button>
                                    @endif
                                    @if (($data->message_type == 2 || $data->message_type == 3) && !$data->is_marked)
                                        <button type="button" data-id="{{ $data->id }}"
                                            class="btn btn-success btn-sm acceptM">Accept</button>
                                    @endif
                                    <button type="button" data-id="{{ $data->id }}"
                                        class="btn btn-danger btn-sm deleteM">Delete</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
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

        .bullet-line-list .old:before {
            border-color: var(--info);
        }
    </style>
    <link href="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css
    " rel="stylesheet">
@endsection
@section('scripts')
    <script src="{{ asset('assets/panel/vendors/bootstrap-toggle/bootstrap-toggle.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';
            $('.markRead').on('click', function() {
                var id = $(this).data('id');
                let _token = $('meta[name="csrf-token"]').attr('content');
            });
            $('.acceptM').on('click', function() {
                var id = $(this).data('id');
                let _token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('user.notification.milkdata') }}",
                    data: {
                        _token: _token,
                        record: id,
                    },
                    success: function(response) {
                        if (response.success) {
                            const wrapper = document.createElement('div');
                            wrapper.classList.add('w-100');
                            wrapper.innerHTML = `
                                <div class="w-100 mb-4">
                                    <p>You are requested for milk quantity of ${response.data.quantity} Liters at rate ${response.data.price}&#8377; /liter</p>
                                </div>
                                <div class="w-100 d-flex justify-content-center align-items-center">
                                    <div class="mr-4">
                                        Trasnporter
                                    </div>
                                    <input id="transportOption" name="transportOption"  type="checkbox">
                                </div>
                                `;
                            swal.fire({
                                title: 'Milk Procure',
                                html: wrapper,
                                confirmButtonText: '@lang('lang.Yes')',
                                showDenyButton: true,
                                cancelButtonText: "@lang('lang.No')",
                                didOpen: () => {
                                    $('#transportOption').bootstrapToggle({
                                        on: 'YES',
                                        off: 'NO'
                                    });
                                },
                                preConfirm: () => {
                                    const is_transport = document.getElementById(
                                        'transportOption').checked;
                                    return {
                                        is_transport
                                    };
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    milkAcceptReject(id, true, result.value.is_transport);
                                } else if (result.isDenied) {
                                    milkAcceptReject(id, false, result.value.is_transport);
                                }
                            });
                        } else {
                            iziToast.error({
                                message: response.message,
                                position: "topRight",
                                timeout: 1500,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        iziToast.error({
                            message: xhr.responseJSON.message,
                            position: "topRight",
                            timeout: 1500,
                        });
                    }
                });
            });

            function milkAcceptReject(record_id, is_accept, is_transport) {
                let _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('user.notification.milkAction') }}",
                    data: {
                        _token: _token,
                        is_accept : is_accept,
                        is_transport: is_transport,
                        record: record_id,
                    },
                    success: function(response) {
                        if (response.success) {
                            iziToast.success({
                                message: response.message,
                                position: "topRight",
                                timeout: 1500,
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
                                timeout: 1500,

                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        iziToast.error({
                            message: xhr.responseJSON.message,
                            position: "topRight",
                            timeout: 1500,
                        });
                    }
                });
            };
            $('.deleteM').on('click', function() {
                var id = $(this).data('id');
                let _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('user.notification.delete') }}",
                    data: {
                        _token: _token,
                        record_id: id,
                    },
                    success: function(response) {
                        if (response.success) {
                            iziToast.success({
                                message: response.message,
                                position: "topRight",
                                timeout: 1500,
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
                                timeout: 1500,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        iziToast.error({
                            message: xhr.responseJSON.message,
                            position: "topRight",
                            timeout: 1500,
                        });
                    }
                });
            });
        })(jQuery);
    </script>
@endsection
