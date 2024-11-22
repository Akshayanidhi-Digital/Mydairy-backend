<link rel="stylesheet" href="{{ asset('assets/iziToast/iziToast.min.css') }}">
<script src="{{ asset('assets/iziToast/iziToast.min.js') }}"></script>
{{-- @if (session()->has('notify'))
    @foreach (session('notify') as $msg)
        <script>
            "use strict";
            iziToast.{{ $msg[0] }}({
                message: "{{ __($msg[1]) }}",
                position: "topRight"
            });
        </script>
    @endforeach
@endif --}}
@if (session()->has('success'))
    <script>
        "use strict";
        iziToast.success({
            message: "{{ __(session('success')) }}",
            position: "topRight",
            timeout: 1500,
        });
        @php
            session()->forget('success');
        @endphp
    </script>
@endif
@if (session()->has('error'))
    <script>
        "use strict";
        iziToast.error({
            message: "{{ __(session('error')) }}",
            position: "topRight",
            timeout: 1500,
        });
        @php
            session()->forget('error');
        @endphp
    </script>
@endif


<script>
    "use strict";

    function notify(status, message) {
        if (typeof message == 'string') {
            iziToast[status]({
                message: message,
                position: "topRight"
            });
        } else {
            $.each(message, function(i, val) {
                iziToast[status]({
                    message: val,
                    position: "topRight",
                });
            });
        }
    }
</script>
