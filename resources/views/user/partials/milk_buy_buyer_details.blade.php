    <div class="text-center pb-4">
        <img src=" {{ asset('assets/default.png') }}" alt="profile" class="img-lg rounded-circle mb-3">
        <div class="mb-3">
            <h3 id="fp_name">{{ $data->name }}</h3>
            <div class="d-flex align-items-center justify-content-center">
                <h5 class="mb-0 me-2 text-muted" id="fp_address">NA</h5>
            </div>
        </div>
        <p id="fp_moreDetails" class="w-75 mx-auto mb-3">
            @lang('lang.Mobile No.'): {{ $data->country_code . $data->mobile }}</p>
        <p id="fp_moreDetails" class="w-75 mx-auto mb-3">
            @lang('lang.Email ID.'): {{ $data->email ?? 'NA' }}</p>

    </div>
