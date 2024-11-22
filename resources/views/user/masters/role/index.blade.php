@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('lang.S.No.')</th>
                                    <th>@lang('Short Name')</th>
                                    <th>@lang('lang.Name')</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($roles->count() > 0)
                                    @foreach ($roles as $key=>$role)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$role->short_name}}</td>
                                        <td>{{$role->name}}</td>
                                        <td><a href="{{route('user.masters.roles.view',$role->role_id)}}" class="badge badge-primary">@lang('lang.View')</a></td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">
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
