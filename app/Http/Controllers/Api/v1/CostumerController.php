<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Helper\Helper;
use App\Models\Buyers;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CostumerController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $costumer = $user->costumers();
        $rules = [
            'costumer_type' => ['required', 'in:' . implode(',', array_column($costumer, 'user_type'))],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = $this->getList($request->input('costumer_type'), $user);
        return Helper::SuccessReturn($data, 'CUSTOMER_LIST');
    }
    private function getList($type, $user)
    {
        if ($type == 'FAR') {
            return  Farmer::where(['parent_id' => $user->user_id, 'trash' => 0])->orderby('farmer_id')
                ->select(
                    'farmer_id as user_id',
                    'name',
                    'father_name',
                    'country_code',
                    'mobile',
                    'email',
                    'is_fixed_rate',
                    'fixed_rate_type',
                    'rate',
                    'fat_rate'
                )->get();
        } else if ($type == 'BYR') {
            return  Buyers::where(['parent_id' => $user->user_id, 'trash' => 0])->orderby('buyer_id')
                ->select(
                    'buyer_id as user_id',
                    'name',
                    'father_name',
                    'country_code',
                    'mobile',
                    'email',
                    'is_fixed_rate',
                    'fixed_rate_type',
                    'rate',
                    'fat_rate'
                )->get();
        } else {
            $users = User::where(['parent_id' => $user->user_id, 'role_id' => $type])
                ->select(
                    'user_id',
                    'name',
                    'father_name',
                    'country_code',
                    'mobile',
                    'email',
                )
                ->get();
            $users->map(function ($user) {
                $user->is_fixed_rate = 0;
                return $user;
            });
            return $users;
        }
    }
    public function add(Request $request)
    {
        $user = $request->user();
        $costumer = $user->costumers();
        $rules = [
            'costumer_type' => ['required', 'in:' . implode(',', array_column($costumer, 'user_type'))],
            'name' => ['required', 'string'],
            'father_name' => ['required', 'string'],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'),              function ($attribute, $value, $fail) {
                $accountType = request()->input('costumer_type');
                if (isset($accountType) &&    $accountType == "FAR") {
                    $existsRule = Rule::unique('farmers', 'mobile');
                } elseif (isset($accountType) && $accountType == 'BYR') {
                    $existsRule = Rule::unique('buyers', 'mobile');
                } else {
                    $existsRule = Rule::unique('users', 'mobile');
                }
                if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                    $fail("The selected mobile number already exist.");
                }
            },],
            'email' => ['nullable', 'email',],
            'is_fixed_rate' => ['nullable', 'in:0,1'],
            'fixed_rate_type' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1;
            }), 'in:0,1'],
            'rate' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1 && request('fixed_rate_type') == 0;
            })],
            'fat_rate' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1 && request('fixed_rate_type') == 1;
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $res = $this->addCostumer($request->input('costumer_type'), $request, $user);
        if ($res) {
            $data = $this->getList($request->input('costumer_type'), $user);
            return Helper::SuccessReturn($data, 'CUSTOMER_ADD');
        } else {
            return Helper::FalseReturn(null, 'SOMETHING_WENT_WRONG');
        }
    }
    private function addCostumer($type, $request, $user)
    {
        if ($type == 'FAR' || $type == 'BYR') {
            $costumer = ($type == 'FAR') ? new Farmer() : new Buyers();
            $costumer->name = $request->name;
            $costumer->father_name = $request->father_name;
            $costumer->country_code = $request->country_code;
            $costumer->mobile = $request->mobile;
            $costumer->password = bcrypt(123456); // send text message for password
            $costumer->parent_id = $user->user_id;
            if (isset($request->email)) {
                $costumer->email = $request->email;
            }
            if (isset($request->is_fixed_rate) && $request->is_fixed_rate == 1) {
                $costumer->is_fixed_rate = 1;
                $costumer->fixed_rate_type = $request->fixed_rate_type;
                if ($request->fixed_rate_type == 0) {
                    $costumer->rate = $request->rate;
                } else {
                    $costumer->fat_rate = $request->fat_rate;
                }
            }
            return  $costumer->save();
        } else {
            $costumer = new User();
            $costumer->name = $request->name;
            $costumer->father_name = $request->father_name;
            $costumer->country_code = $request->country_code;
            $costumer->mobile = $request->mobile;
            $costumer->password = bcrypt(123456); // send text message for password
            $costumer->email = $request->input('email', null);
            $costumer->parent_id = $user->user_id;
            $costumer->role_id = $request->costumer_type;
            $costumer->role = 2;
            return  $costumer->save();
        }
    }
    public function update(Request $request)
    {
        $user = $request->user();
        $costumer = $user->costumers();
        $rules = [
            'costumer_type' => ['required', 'in:' . implode(',', array_column($costumer, 'user_type'))],
            'costumer_id' => ['required', function ($attribute, $value, $fail) {
                $accountType = request()->input('costumer_type');
                $parent_id = request()->user()->user_id;
                if (isset($accountType) &&    $accountType == "FAR") {
                    $existsRule = Rule::exists('farmers', 'farmer_id')->where('parent_id', $parent_id);
                } elseif (isset($accountType) && $accountType == 'BYR') {
                    $existsRule = Rule::exists('buyers', 'buyer_id')->where('parent_id', $parent_id);
                } else {
                    $existsRule = Rule::exists('users', 'user_id')->where('parent_id', $parent_id);
                }
                if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                    $fail("The selected user does not exist in the specified account type.");
                }
            },],
            'name' => ['required', 'string'],
            'father_name' => ['required', 'string'],
            'country_code' => ['required'],
            'mobile' => [
                'required',
                'numeric',
                Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                function ($attribute, $value, $fail) {
                    $accountType = request()->input('costumer_type');
                    $user_id = request()->input('costumer_id');
                    if (isset($accountType) &&    $accountType == "FAR") {
                        $existsRule = Rule::unique('farmers', 'mobile')->ignore($user_id, 'farmer_id');
                    } elseif (isset($accountType) && $accountType == 'BYR') {
                        $existsRule = Rule::unique('buyers', 'mobile')->ignore($user_id, 'buyer_id');
                    } else {
                        $existsRule = Rule::unique('users', 'mobile')->ignore($user_id, 'user_id');
                    }
                    if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                        $fail("The selected mobile number already exist.");
                    }
                },
            ],
            'email' => ['nullable', 'email',],
            'is_fixed_rate' => ['nullable', 'in:0,1'],
            'fixed_rate_type' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1;
            }), 'in:0,1'],
            'rate' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1 && request('fixed_rate_type') == 0;
            })],
            'fat_rate' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1 && request('fixed_rate_type') == 1;
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $res = $this->updateCostumer($request->input('costumer_type'), $request, $user);
        if ($res) {
            $data = $this->getList($request->input('costumer_type'), $user);
            return Helper::SuccessReturn($data, 'CUSTOMER_UPDATE');
        } else {
            return Helper::FalseReturn(null, 'SOMETHING_WENT_WRONG');
        }
    }
    private function updateCostumer($type, $request, $user)
    {
        $user_id = $request->input('costumer_id');
        if ($type == 'FAR' || $type == 'BYR') {
            $costumer = ($type == 'FAR') ? Farmer::where(['farmer_id' => $user_id, 'parent_id' => $user->user_id])->first() : Buyers::where(['buyer_id' => $user_id, 'parent_id' => $user->user_id])->first();
            $costumer->name = $request->name;
            $costumer->father_name = $request->father_name;
            $costumer->country_code = $request->country_code;
            $costumer->mobile = $request->mobile;
            $costumer->parent_id = $user->user_id;
            $costumer->email = $request->input('email', $costumer->email);
            if (isset($request->is_fixed_rate) && $request->is_fixed_rate == 1) {
                $costumer->is_fixed_rate = 1;
                $costumer->fixed_rate_type = $request->fixed_rate_type;
                if ($request->fixed_rate_type == 0) {
                    $costumer->rate = $request->rate;
                } else {
                    $costumer->fat_rate = $request->fat_rate;
                }
            }
            return  $costumer->update();
        } else {
            $costumer = User::where(['user_id' => $user_id, 'parent_id' => $user->user_id])->first();
            $costumer->name = $request->name;
            $costumer->father_name = $request->father_name;
            $costumer->country_code = $request->country_code;
            $costumer->mobile = $request->mobile;
            $costumer->parent_id = $user->user_id;
            $costumer->role_id = $request->costumer_type;
            return  $costumer->update();
        }
    }
    public function status(Request $request)
    {
        $user = $request->user();
        $costumer = $user->costumers();
        $rules = [
            'costumer_type' => ['required', 'in:' . implode(',', array_column($costumer, 'user_type'))],
            'costumer_id' => ['required', function ($attribute, $value, $fail) {
                $accountType = request()->input('costumer_type');
                $parent_id = request()->user()->user_id;
                if (isset($accountType) &&    $accountType == "FAR") {
                    $existsRule = Rule::exists('farmers', 'farmer_id')->where('parent_id', $parent_id);
                } elseif (isset($accountType) && $accountType == 'BYR') {
                    $existsRule = Rule::exists('buyers', 'buyer_id')->where('parent_id', $parent_id);
                } else {
                    $existsRule = Rule::exists('users', 'user_id')->where('parent_id', $parent_id);
                }
                if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                    $fail("The selected user does not exist in the specified account type.");
                }
            },],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $res = $this->updateStatusCostumer($request->input('costumer_type'), $request, $user);
        if ($res) {
            $data = $this->getList($request->input('costumer_type'), $user);
            return Helper::SuccessReturn($data, 'CUSTOMER_STATUS_UPDATE');
        } else {
            return Helper::FalseReturn(null, 'SOMETHING_WENT_WRONG');
        }
    }

    private function updateStatusCostumer($type, $request, $user, $delete = false)
    {
        $user_id = $request->input('costumer_id');
        if ($type == 'FAR' || $type == 'FAR') {
            $costumer = ($type == 'FAR') ? Farmer::where(['farmer_id' => $user_id, 'parent_id' => $user->user_id])->first() : Buyers::where(['buyer_id' => $user_id, 'parent_id' => $user->user_id])->first();
            if ($delete) {
                return $costumer->delete();
            }
            $costumer->trash = ($costumer->trash) ? false : true;
            return $costumer->update();
        } else {
            $costumer = User::where(['user_id' => $user_id, 'parent_id' => $user->user_id])->first();
            if ($delete) {
                return $costumer->forceDelete();
            }
            $costumer->is_blocked = ($costumer->is_blocked) ? false : true;
            return $costumer->update();
        }
    }
    public function delete(Request $request)
    {
        $user = $request->user();
        $costumer = $user->costumers();
        $rules = [
            'costumer_type' => ['required', 'in:' . implode(',', array_column($costumer, 'user_type'))],
            'costumer_id' => ['required', function ($attribute, $value, $fail) {
                $accountType = request()->input('costumer_type');
                $parent_id = request()->user()->user_id;
                if (isset($accountType) &&    $accountType == "FAR") {
                    $existsRule = Rule::exists('farmers', 'farmer_id')->where('parent_id', $parent_id);
                } elseif (isset($accountType) && $accountType == 'BYR') {
                    $existsRule = Rule::exists('buyers', 'buyer_id')->where('parent_id', $parent_id);
                } else {
                    $existsRule = Rule::exists('users', 'user_id')->where('parent_id', $parent_id);
                }
                if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                    $fail("The selected user does not exist in the specified account type.");
                }
            },],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $res = $this->updateStatusCostumer($request->input('costumer_type'), $request, $user, true);
        if ($res) {
            $data = $this->getList($request->input('costumer_type'), $user);
            return Helper::SuccessReturn($data, 'CUSTOMER_DELETED');
        } else {
            return Helper::FalseReturn(null, 'SOMETHING_WENT_WRONG');
        }
    }
}
