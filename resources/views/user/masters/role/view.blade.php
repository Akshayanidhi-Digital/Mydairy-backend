@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('user.masters.roles.update',$role->role_id)}}" method="post">
                        @csrf
                        <div class="row">
                            @foreach ($groupedPermissions as $key => $value)
                                <div class="col-md-12">
                                    <h4 class="mt-3 text-primary text-capitalize">{{ $key }} Permission</h4>
                                </div>
                                @foreach ($value as $permission)
                                    <div class="col-md-4">
                                        <div class="row my-2">
                                            <label class="col-sm-9 col-form-label text-capitalize">{{ str_replace('.', ' ', $permission->permission_name) }}</label>
                                            <label class="col-sm-3 checkbox-inline">
                                                <input type="checkbox" data-style="quick" name="{{ $permission->permission_name }}"
                                                    @checked($permission->access) data-on="@lang('lang.on')"
                                                    data-off="@lang('lang.off')" data-toggle="toggle">
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary me-2">@lang('lang.:name Update',['name'=>__('lang.Role Permission')])</button>
                        <a href="{{route('user.masters.roles.list')}}" class="btn btn-light">@lang('lang.Back')</a>
                    </form>
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

        .toggle.btn {
            width: 100% !important;
            width: -webkit-fill-available !important;
            height: 40px !important;
        }

        .toggle.btn,
        .toggle-handle.btn {
            border-radius: 10px;
        }

        .toggle-handle.btn {
            background: #979797;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{ asset('assets/panel/vendors/bootstrap-toggle/bootstrap-toggle.min.js') }}"></script>
@endsection
