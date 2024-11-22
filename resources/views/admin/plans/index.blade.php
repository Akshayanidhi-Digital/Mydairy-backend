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
                                    <th>Plan id</th>
                                    <th>name</th>
                                    <th>category</th>
                                    <th>Price <br> (in &#8377; )</th>
                                    <th>Duration</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @empty($packages)
                                    <tr>
                                        <td colspan="7">
                                            <div class="d-flex justify-content-center">No plans Found</div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($packages as $package)
                                        <tr>
                                            <td>{{ $package->plan_id }}</td>
                                            <td>{{ $package->name }}</td>
                                            <td>{{ $package->category }}</td>
                                            <td>{{ $package->price }}</td>
                                            <td>{{ $package->duration }} {{ $package->duration_type }}</td>
                                            <td>{{ $package->description }}</td>
                                            <td><a href="{{route('admin.plans.edit',$package->id)}}" class="btn btn-sm btn-primary">Edit</a></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="7">
                                            {{ $packages->links('pagination::bootstrap-5') }}
                                        </td>
                                    </tr>
                                @endempty


                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
