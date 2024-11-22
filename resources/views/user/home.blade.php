@extends('layouts.app')

@section('content')
    <div class="row bg-secondary py-4 rounded" id="cards-dragula">
        @foreach ($cards as $card)
            <div class="col-12 col-sm-6 col-md-6 col-xl-3 grid-margin stretch-card">
                <div class="card {{$card['class']}}">
                    <div class="card-body">
                        <p class="mb-4">{{$card['title']}}</p>
                        @php
                        $variableName = $card['subtitle'];
                    @endphp
                        <p class="fs-30 mb-2">{{ number_format($$variableName,2) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/dragula/dragula.min.css') }}">
@endsection
@section('scripts')
    {{-- dragula --}}
    <script src="{{ asset('assets/panel/vendors/dragula/dragula.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';
            var container = document.getElementById('cards-dragula');
            dragula([container]);
        })(jQuery);
    </script>
@endsection
