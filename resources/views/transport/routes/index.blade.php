@extends('layouts.transport')
@section('content')
<div class="row">
    <div class="col-sm-12 ">
        <div class="card grid-margin">
            <div class="card-body">
                <p class="card-title mb-2">@lang('lang.Active :name List',['name'=>__('lang.Route')])</p>
                @include('transport.routes.table',['datas'=>$routes_list,'paginate'=>true])
            </div>
        </div>
    </div>
</div>
@endsection
