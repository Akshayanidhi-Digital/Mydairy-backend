@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title ?? '' }}</h4>
                    <form action="{{ route('admin.apphelp.update', $help->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="help_name">Help Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="help_name" placeholder="name"
                                    value="{{ old('help_name', $help->name) }}">
                                @error('help_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="help_url">Help Url</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="https://example.com" name="help_url"
                                    value="{{ old('help_url', $help->url) }}">
                                @error('help_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="help_image">Help Imgae/Thumbnail</label>
                            <input type="file" accept="image/png, image/jpeg, image/jpeg" name="help_image"
                                class="dropify">
                            @error('help_image')
                                <label id="help_image-error" class="error mt-2 text-danger"
                                    for="help_image">{{ $message }}</label>
                            @enderror
                        </div>
                        <button class="btn btn-primary">Update Help</button>
                        <a href="{{ route('admin.apphelp.list') }}" class="btn  btn-outline-primary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/dropify/dropify.min.css') }}" />
@endsection
@section('scripts')
    <script src="{{ asset('assets/panel/vendors/dropify/dropify.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';
            $('.dropify').dropify({
                messages: {
                    default: "Drag and Drop or clik here to upload Help Imgae/Thumbnail.",
                    remove: "@lang('lang.Remove')",
                    error: "@lang('lang.Sorry, the file is too large')"
                },
                defaultFile: '{{ asset($help->image_path) }}'
            });
        })(jQuery);
    </script>
@endsection
