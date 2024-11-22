@if ($type == 'default')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group @error('buyer') has-danger @enderror">
                <label for="buyer">@lang('lang.Buyer')</label>
                <select class="buyer_select w-100 @error('buyer') form-control-danger @enderror" name="buyer"
                    id="buyer">
                    <option value="default">@lang('lang.Select :name', ['name' => __('lang.Buyer')])</option>
                </select>
                @error('buyer')
                    <label id="buyer-error" class="error mt-2 text-danger" for="buyer">{{ $message }}</label>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group @error('milk_type') has-danger @enderror">
                <label for="milk_type">@lang('lang.Milk Type')</label>
                <select class="milk_type_select w-100 @error('milk_type') form-control-danger @enderror"
                    name="milk_type" id="milk_type">
                    <option value="default">@lang('lang.Select :name', ['name' => __('lang.Milk Type')])</option>
                    @foreach (MILK_TYPE_LIST as $key => $value)
                        <option @if (old('milk_type') && old('milk_type') == $key) selected @endif value="{{ $key }}">
                            @lang('lang.' . $value)</option>
                    @endforeach

                </select>
            </div>
            @error('milk_type')
                <label id="milk_type-error" class="error mt-2 text-danger" for="milk_type">{{ $message }}</label>
            @enderror
        </div>

        <div class="col-md-6">
            <div class="form-group @error('quantity') has-danger @enderror">
                <label for="quantity">@lang('lang.Quantity')</label>
                <input type="number" min="0" @disabled(old('quantity') ? false : true)
                    class="form-control @error('quantity') form-control-danger @enderror " id="quantity"
                    placeholder="0.0" value="{{ old('quantity') }}" name="quantity">
                @error('quantity')
                    <label id="quantity-error" class="error mt-2 text-danger" for="quantity">{{ $message }}</label>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group @error('fat') has-danger @enderror">
                <label for="fat">@lang('lang.FAT')</label>
                <input type="number" min="0" @disabled(old('fat') ? false : true)
                    class="form-control @error('fat') form-control-danger @enderror " id="fat" placeholder="0.0"
                    value="{{ old('fat') }}" name="fat">
                @error('fat')
                    <label id="fat-error" class="error mt-2 text-danger" for="fat">{{ $message }}</label>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group @error('snf') has-danger @enderror">
                <label for="snf">@lang('lang.SNF')</label>
                <input type="number" min="0" @disabled(old('snf') ? false : true)
                    class="form-control @error('snf') form-control-danger @enderror " id="snf" placeholder="0.0"
                    value="{{ old('snf') }}" name="snf">
                @error('snf')
                    <label id="snf-error" class="error mt-2 text-danger" for="snf">{{ $message }}</label>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group @error('clr') has-danger @enderror">
                <label for="clr">@lang('lang.CLR')</label>
                <input type="number" min="0" @disabled(old('clr') ? false : true)
                    class="form-control @error('clr') form-control-danger @enderror " id="clr" placeholder="0.0"
                    value="{{ old('clr') }}" name="clr">
                @error('clr')
                    <label id="clr-error" class="error mt-2 text-danger" for="clr">{{ $message }}</label>
                @enderror
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-md-6">
            <div class="form-group @error('name') has-danger @enderror">
                <label for="name">@lang('lang.Name')</label>
                <input type="text" class="form-control @error('name') form-control-danger @enderror" id="name"
                    placeholder="@lang('lang.Name')" name="name" value="{{ old('name') }}">
                @error('name')
                    <label id="name-error" class="error mt-2 text-danger" for="name">{{ $message }}</label>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group @error('country_code') has-danger @enderror">
                <label for="country_code">@lang('lang.Country')</label>
                <select class="country_select w-100 @error('country_code') form-control-danger @enderror"
                    name="country_code" id="country_code">
                    <option value="default">@lang('lang.Select :name', ['name' => __('lang.Country')])</option>
                    <option value="+91">@lang('lang.India')</option>
                </select>
            </div>
            @error('country_code')
                <label id="country_code-error" class="error mt-2 text-danger"
                    for="country_code">{{ $message }}</label>
            @enderror
        </div>
        <div class="col-md-6">
            <div class="form-group @error('mobile') has-danger @enderror">
                <label for="mobile">@lang('lang.Mobile No.')</label>
                <input type="number" min="0"
                    class="form-control @error('mobile') form-control-danger @enderror " id="mobile"
                    placeholder="@lang('lang.Mobile No.')" value="{{ old('mobile') }}" name="mobile">
                @error('mobile')
                    <label id="mobile-error" class="error mt-2 text-danger" for="mobile">{{ $message }}</label>
                @enderror

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group @error('milk_type') has-danger @enderror">
                <label for="milk_type">@lang('lang.Milk Type')</label>
                <select class="milk_type_select w-100 @error('milk_type') form-control-danger @enderror"
                    name="milk_type" id="milk_type">
                    <option value="default">@lang('lang.Select :name', ['name' => __('lang.Milk Type')])</option>
                    @foreach (MILK_TYPE_LIST as $key => $value)
                        <option @if (old('milk_type') && old('milk_type') == $key) selected @endif value="{{ $key }}">
                            @lang('lang.' . $value)</option>
                    @endforeach
                </select>
            </div>
            @error('milk_type')
                <label id="milk_type-error" class="error mt-2 text-danger" for="milk_type">{{ $message }}</label>
            @enderror
        </div>

        <div class="col-md-6">
            <div class="form-group @error('quantity') has-danger @enderror">
                <label for="quantity">@lang('lang.Quantity')</label>
                <input type="number" min="0"
                    class="form-control @error('quantity') form-control-danger @enderror " id="quantity"
                    placeholder="0.0" value="{{ old('quantity') }}" name="quantity">
                @error('quantity')
                    <label id="quantity-error" class="error mt-2 text-danger" for="quantity">{{ $message }}</label>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group @error('fat') has-danger @enderror">
                <label for="fat">@lang('lang.FAT')</label>
                <input type="number" min="0"
                    class="form-control @error('fat') form-control-danger @enderror " id="fat"
                    placeholder="0.0" value="{{ old('fat') }}" name="fat">
                @error('fat')
                    <label id="fat-error" class="error mt-2 text-danger" for="fat">{{ $message }}</label>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group @error('snf') has-danger @enderror">
                <label for="snf">@lang('lang.SNF')</label>
                <input type="number" min="0"
                    class="form-control @error('snf') form-control-danger @enderror " id="snf"
                    placeholder="0.0" value="{{ old('snf') }}" name="snf">
                @error('snf')
                    <label id="snf-error" class="error mt-2 text-danger" for="snf">{{ $message }}</label>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group @error('clr') has-danger @enderror">
                <label for="clr">@lang('lang.CLR')</label>
                <input type="number" min="0"
                    class="form-control @error('clr') form-control-danger @enderror " id="clr"
                    placeholder="0.0" value="{{ old('clr') }}" name="clr">
                @error('clr')
                    <label id="clr-error" class="error mt-2 text-danger" for="clr">{{ $message }}</label>
                @enderror
            </div>
        </div>
    </div>
@endif
