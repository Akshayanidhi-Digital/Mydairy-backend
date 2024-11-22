@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12  grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-item-center">
                        <h4 class="card-title">{{ $title }}</h4>
                        <button type="button" class="btn btn-sm btn-primary" id="editRate">Edit Rate</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fat/SNF</th>
                                    @foreach ($ratechart['snf'] as $snf)
                                        <th>{{ $snf }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ratechart['fat'] as $key => $fat)
                                    <tr>
                                        <td>{{ $fat }}</td>
                                        @foreach ($ratechart['snf'] as $key2 => $snf)
                                            <td>{{ $ratechart['rate'][$key][$key2] }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
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

            $('#editRate').on('click', function() {
                swal.fire({
                    title: "@lang('lang.Add :name', ['name' => __('lang.Product Group')])", // 'Add Product Group'
                    html: `
        <div style="text-align: center;">
            <!-- Radio Inputs -->
            <div>
                <label>
                    <input type="radio" name="group" value="option1" style="margin-right: 10px;">
                    Option 1
                </label>
                <label>
                    <input type="radio" name="group" value="option2" style="margin-left: 20px;">
                    Option 2
                </label>
            </div>
            <br>
            <!-- Inline Fat Inputs -->
            <div style="display: inline-block; margin-right: 20px;">
                <label>Fat From:</label>
                <input type="text" id="fat_from" placeholder="From" style="margin-left: 5px;">
                <label>To:</label>
                <input type="text" id="fat_to" placeholder="To" style="margin-left: 5px;">
            </div>
            <!-- Inline SNF Inputs -->
            <div style="display: inline-block;">
                <label>SNF From:</label>
                <input type="text" id="snf_from" placeholder="From" style="margin-left: 5px;">
                <label>To:</label>
                <input type="text" id="snf_to" placeholder="To" style="margin-left: 5px;">
            </div>
        </div>
    `,
                    showCancelButton: true,
                    confirmButtonText: "@lang('lang.Ok')",
                    cancelButtonText: "@lang('lang.Cancel')",
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-danger',
                    },
                    buttonsStyling: false
                })
            })
        })(jQuery);
    </script>
@endsection
