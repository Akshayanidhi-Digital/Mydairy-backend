@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-sm-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="float-right">
                    <a href="{{ route('user.plans.create') }}" class="btn btn-sm btn-primary">Add Plan</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>@lang('lang.S.No.')</th>
                                <th>Plan Name</th>
                                <th>Payment ID</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Start date</th>
                                <th>End Date</th>
                                <th>@lang('lang.Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $key => $record)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $record->plan->name ?? '' }}</td>
                                <td>{{ $record->payment_id ?? '' }}</td>
                                <td>{{ $record->payment_method ?? '' }}</td>
                                <td>{!! getPaymentStatus($record->payment_status) !!}</td>
                                <td>{{ $record->amount  ?? ''}}</td>
                                <td>{!! getPaymentPlanStatus($record->status, $record->end_date) !!}</td>
                                <td>{{ $record->start_date ?? '' }}</td>
                                <td>{{ $record->end_date ?? '' }}</td>
                                <td>
                                    <button id="actionOnrecords" type="button"
                                        data-payment-status="{{ $record->payment_status ?? '' }}"
                                        data-record-id="{{ $record->id ?? '' }}"
                                        data-record-status="{{ $record->status ?? '' }}"
                                        class="actionOnrecords btn btn btn-outline-secondary btn-icon">
                                        <i class="fa fa-ellipsis-v text-primary"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @if ($records->total() > env('PER_PAGE_RECORDS'))
                            <tr>
                                <td colspan="10">
                                    <div class="d-flex justify-content-center align-items-center">
                                        {{ $records->links('pagination::bootstrap-4') }}
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

        function planStatus(status, record_id) {
            if (status == 0) {
                swal.fire({
                    title: '@lang('
                    lang.Are you sure ? ')',
                    text : 'You want to activate this plan. If you have current plan active you will lose that plan benifits.',
                    icon: 'warning',
                    confirmButtonText: '@lang('
                    lang.Yes ')',
                    showCancelButton: true,
                    cancelButtonText: "@lang('lang.No')",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href =
                            "{{ route('user.plans.activate', ['id' => ':id']) }}"
                            .replace(':id', record_id);
                    }
                });
            } else {
                swal.fire({
                    title: 'Plan Status',
                    text: (status == 2) ? "Plan expired " : "Current Active plan",
                    icon: (status == 2) ? "error" : "success",
                });
            }
        }

        function planpaymentStatus(pay_status) {
            if (pay_status == 1) {
                swal.fire({
                    title: '@lang('
                    lang.Are you sure ? ')',
                    text : 'Make Complete Payment',
                    icon: 'warning',
                    confirmButtonText: '@lang('
                    lang.Yes ')',
                    showCancelButton: true,
                    cancelButtonText: "@lang('lang.No')",
                }).then((result) => {
                    if (result.isConfirmed) {

                        window.location.href =
                            "{{ route('user.plans.pay', ['id' => ':id']) }}"
                            .replace(':id', record_id);
                    }
                });
            } else {
                swal.fire({
                    title: 'Payment Status',
                    text: (pay_status == 0) ? "Payment status panding " : "Payment Completed",
                    icon: (pay_status == 0) ? "error" : "success",
                });
            }
        }
        $.contextMenu({
            selector: '#actionOnrecords',
            className: 'contextmenu-customwidth',
            trigger: 'left',
            delay: 500,
            autoHide: true,
            callback: function(key, options) {
                var record_id = $(this).data('record-id');
                var status = $(this).data('record-status');
                var pay_status = $(this).data('payment-status');
                switch (key) {
                    case "pay_status":
                        planpaymentStatus(pay_status);
                        break;
                    case "print":
                        window.location.href =
                            "{{ route('user.plans.print', ['id' => ':id']) }}"
                            .replace(':id', record_id);
                        break;
                    case "status":
                        planStatus(status, record_id)
                        break;
                    default:
                        break;

                }
            },
            items: {
                "print": {
                    name: "@lang('lang.Print')",
                    icon: "fa-print"
                },
                "pay_status": {
                    name: "@lang('lang.Payment Status')",
                    icon: "fa-money"
                },
                "status": {
                    name: "@lang('lang.Status')",
                    icon: "fa-ban"
                },
            }
        });


    })(jQuery);
</script>
@endsection