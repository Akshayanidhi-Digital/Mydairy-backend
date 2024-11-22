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
                                    <th>user ID</th>
                                    <th>Name</th>
                                    <th>Father Name</th>
                                    <th>Mobile No</th>
                                    <th>Dairy name</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Plan Status</th>
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
                                            <td>{{ $data->user_id }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->father_name ?? 'N/A' }}</td>
                                            <td><a
                                                    href="tel:{{ $data->country_code . $data->mobile }}">{{ $data->country_code . ' ' . $data->mobile }}</a>
                                            </td>
                                            <td>{{ $data->profile->dairy_name }}</td>
                                            <td><img src="{{ asset($data->profile->image_path) }}" alt="{{ $data->name }}">
                                            </td>
                                            <td>{!! getUserProfileStatus($data->is_blocked) !!}</td>
                                            <td>{!! $data->is_subdairy()
                                                ? '<div class="badge badge-info">' . $data->role_name . '</div>'
                                                : getUserPlanStatus($data) !!}</td>
                                            <td>
                                                <button class="btn btn-primary" onclick="updateStatus('{{$data->user_id}}')">Update Status</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($datas->total() > env('PER_PAGE_RECORDS'))
                                        <tr>
                                            <td colspan="9">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    {{ $datas->links('pagination::bootstrap-4') }}
                                                </div>
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
@section('scripts')
    <script>
        (function($) {

            updateStatus = function(id) {
                swal.fire({
                    title: 'Are you sure?',
                    text: "You want to change this User status.",
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
                            url: "{{ route('admin.user.status') }}",
                            type: "POST",
                            data: {
                                _token: _token,
                                user_id: id
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
