<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>@lang('lang.S.No.')</th>
                <th>@lang('lang.Route ID')</th>
                <th>@lang('lang.Route Name')</th>
                <th>@lang('lang.Driver')</th>
                <th>@lang('lang.Action')</th>
            </tr>
        </thead>
        <tbody>
            @if ($datas->count() > 0)
                @isset($paginate)
                    @foreach ($datas as $key => $data)
                        <tr>
                            <td>{{ $datas->firstItem() + $key }}</td>
                            <td>{{ $data->route_id }}</td>
                            <td>{{ $data->route_name }}</td>
                            <td>
                                @if ($data->is_driver)
                                    {{ $data->driver->name }}
                                @else
                                    NA
                                @endif
                            </td>
                            <td>
                                <button type="button" data-route-id="{{ $data->route_id }}" id="actionOnRoutes"
                                    class="btn btn-primary btn-rounded btn-icon">
                                    <i class="fa fa-cogs" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    @if ($datas->total() > env('PER_PAGE_RECORDS'))
                        <tr>
                            <td colspan="5">
                                <div class="d-flex justify-content-center align-items-center">
                                    {{ $datas->links('pagination::bootstrap-4') }}
                                </div>
                            </td>
                        </tr>
                    @endif
                @else
                    @foreach ($datas as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->route_id }}</td>
                            <td>{{ $data->route_name }}</td>
                            <td>
                                @if ($data->is_driver)
                                    {{ $data->driver->name }}
                                @else
                                    NA
                                @endif
                            </td>
                            <td>
                                <button type="button" data-route-id="{{ $data->route_id }}" id="actionOnRoutes"
                                    class="btn btn-primary btn-rounded btn-icon">
                                    <i class="fa fa-cogs" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5">
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="{{ route('transport.route.index') }}" class="btn btn-outline-primary">View More</a>
                            </div>
                        </td>
                    </tr>
                @endisset
            @else
                <tr>
                    <td colspan="5">
                        <div class="d-flex justify-content-center">
                            @lang('lang.No Records Found')
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
