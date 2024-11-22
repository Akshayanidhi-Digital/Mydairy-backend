@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title ?? '' }}</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tnx ID</th>
                                    <th>User</th>
                                    <th>User Image</th>
                                    <th>Plan</th>
                                    <th>Payment Status</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @empty($datas)
                                    <tr>
                                        <td colspan="7">
                                            <div class="d-flex justify-content-center">No plans Found</div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($datas as $data)
                                        <tr>
                                            <td>{{ $data->payment_id }}</td>
                                            <td><a
                                                    href="">{{ $data->user->name . ' S/o ' . $data->user->father_name }}</a>
                                            </td>
                                            <td><img src="{{ asset(getProfileImage($data->user->user_id)) }}"
                                                    alt="{{ $data->user->name }}"></td>
                                            <td><a href="">{{ $data->plan->name }}</a></td>
                                            <td>{!! getPaymentStatus($data->payment_status) !!}</td>
                                            <td>&#8377; {{ $data->amount }}</td>
                                            <td>
                                                <a href="{{ route('admin.payments.print', $data->payment_id) }}" target="_blank"
                                                    class="btn btn-primary">View & Print</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($datas->total() > env('PER_PAGE_RECORDS'))
                                        <tr>
                                            <td colspan="7">
                                                {{ $datas->links('pagination::bootstrap-5') }}
                                            </td>
                                        </tr>
                                    @endif
                                @endempty
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
