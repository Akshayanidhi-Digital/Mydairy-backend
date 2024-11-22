<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Helper\Helper;
use App\Models\DealerRoles;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Rules\PermissionExists;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\DealerRolePermissions;
use App\Models\DealerPermissionAllowed;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\IsDairyMiddleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MasterController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            IsDairyMiddleware::class
        ];
    }

    public function roles(Request $request)
    {
        $roles = DealerRoles::all()->select('role_id', 'short_name', 'name');
        return Helper::SuccessReturn($roles, 'ROLE_LIST_FETCHED');
    }
    public function rolesView(Request $request)
    {
        $user = $request->user();
        $rules = [
            'role_id' => ['required', Rule::exists('dealer_roles', 'role_id')],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $role = DealerRoles::where('role_id', $request->role_id)->first();
        $permissionIds = DealerRolePermissions::pluck('permission_id');
        $tempermission = DealerPermissionAllowed::where(['user_id' => $user->user_id, 'role_id' => $request->role_id])->whereIn('permission_id', $permissionIds)->select('permission_id', 'access')->get();
        if ($tempermission->count() != $permissionIds->count()) {
            foreach ($permissionIds as $key => $value) {
                DealerPermissionAllowed::updateOrcreate(
                    ['user_id' => $user->user_id, 'role_id' => $request->role_id, 'permission_id' => $value],
                );
            }
            $permissions =  DealerPermissionAllowed::where(['user_id' => $user->user_id, 'role_id' => $request->role_id])->whereIn('permission_id', $permissionIds)->select('permission_id', 'access')->get();
        } else {
            $permissions = $tempermission;
        }
        $groupedPermissions = $permissions->groupBy(function ($item) {
            return explode('.', $item->permission_name)[0];
        });
        return Helper::SuccessReturn($groupedPermissions, 'ROLE_LIST_FETCHED');
    }
    public function rolesUpdate(Request $request)
    {
        $user = $request->user();
        $rules = [
            'role_id' => ['required', Rule::exists('dealer_roles', 'role_id')],
            'permissions'=>['nullable','array'],
        ];
        foreach ($request->input('permissions', []) as $key => $value) {
            $rules["permissions.$key"] = ['boolean',new PermissionExists];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        foreach ($request->input('permissions', []) as $key => $value) {
            DealerPermissionAllowed::where(['user_id'=>$user->user_id,'role_id'=>$request->role_id,'permission_id'=>$key])->update(['access'=>$value]);
        }
        return Helper::SuccessReturn(null,'ROLE_PERMISSION_UPDATED');
    }
    public function childDairy(Request $request)
    {
        $user = $request->user();
        return $this->childDairyDataReturn($user->user_id, 'CHILD_DAIRY_LIST');
    }
    private function childDairyDataReturn($user_id, $message)
    {
        $data =  User::where(['parent_id' => $user_id])->with('profile')->get();
        return Helper::SuccessReturn($data, $message);
    }
    public function childDairyAdd(Request $request)
    {
        $user = $request->user();
        $rules = [
            "name" => ['required', 'string', "max:100"],
            "email" => ['nullable', 'email', "unique:users,email"],
            'mobile' => [
                'nullable', 'numeric',
                Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                Rule::unique('users', 'mobile')->ignore($user->id)
            ],
            'country_code' => ['nullable', 'string'],
            "role" => ['required', Rule::exists('dealer_roles', 'role_id')],
            "dairy_name" => ['required'],
            "address" => ['required', "string"],
            "password" => ['required', "min:6"],
            "confirm_password" => ['required', 'min:6', 'same:password'],
            "latitude" => ['nullable', "numeric"],
            "longitude" => ['nullable', "numeric"],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $dairy = new User();
        $dairy->parent_id = auth()->user()->user_id;
        $dairy->name = $request->name;
        $dairy->email = (isset($request->email)) ? $request->email : null;
        $dairy->mobile = $request->mobile;
        $dairy->country_code = isset($request->country_code) ? $request->country_code : '+91';
        $dairy->role_id = $request->role;
        $dairy->role = 2;
        $dairy->password = bcrypt($request->password);
        $dairy->save();
        $dairy_profile = new UserProfile();
        $dairy_profile->user_id = $dairy->user_id;
        $dairy_profile->dairy_name = $request->dairy_name;
        $dairy_profile->address = $request->address;
        $dairy_profile->latitude = $request->latitude;
        $dairy_profile->longitude = $request->longitude;
        $dairy_profile->save();
        return $this->childDairyDataReturn($user->user_id, 'CHILD_DAIRY_ADD');
    }
    public function childDairyUpdate(Request $request)
    {
        $user = $request->user();
        $rules = [
            'child_Dairy_id' => ['required', Rule::exists('users', 'user_id')->where(function ($query) use ($user) {
                $query->where('parent_id', $user->user_id);
            }),],
            "name" => ['nullable', 'string', "max:100"],
            "email" => ['nullable', 'email', Rule::unique('users', 'email')->ignore($request->child_Dairy_id, 'user_id')],
            'mobile' => [
                'nullable', 'numeric',
                Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                Rule::unique('users', 'mobile')->ignore($request->child_Dairy_id, 'user_id')
            ],
            'country_code' => ['nullable', 'string'],
            "role" => ['nullable', Rule::exists('dealer_roles', 'role_id')],
            "dairy_name" => ['nullable'],
            "address" => ['nullable', "string"],
            "latitude" => ['nullable', "numeric"],
            "longitude" => ['nullable', "numeric"],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $dairy = User::where(['user_id' => $request->child_Dairy_id, 'parent_id' => $user->user_id])->first();
        $dairy->parent_id = auth()->user()->user_id;
        $dairy->name = $request->input('name', $dairy->name);
        $dairy->email = $request->input('email', $dairy->email);
        $dairy->mobile = $request->input('mobile', $dairy->mobile);
        $dairy->country_code = $request->input('country_code', $dairy->country_code);
        $dairy->role_id = $request->input('role', $dairy->role_id);
        $dairy->update();
        $dairy_profile = UserProfile::where(['user_id' => $request->child_Dairy_id,])->first();
        $dairy_profile->dairy_name = $request->input('dairy_name', $dairy_profile->dairy_name);
        $dairy_profile->address = $request->input('address', $dairy_profile->address);
        $dairy_profile->latitude = $request->input('latitude', $dairy_profile->latitude);
        $dairy_profile->longitude = $request->input('longitude', $dairy_profile->longitude);
        $dairy_profile->update();
        return $this->childDairyDataReturn($user->user_id, 'CHILD_DAIRY_UPDATE');
    }
    public function childDairyBlockUnblock(Request $request)
    {
        $user = $request->user();
        $rules = [
            'child_Dairy_id' => ['required', Rule::exists('users', 'user_id')->where(function ($query) use ($user) {
                $query->where('parent_id', $user->user_id);
            }),],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $childUser = User::where(['user_id' => $request->child_Dairy_id, 'parent_id' => $user->user_id])->first();
        if (!$childUser) {
            return Helper::FalseReturn(null, 'CHILD_DAIRY_NOT_FOUND');
        }
        if ($childUser->is_blocked == 0) {
            $childUser->is_blocked = 1;
            $childUser->update();
            return $this->childDairyDataReturn($user->user_id, 'CHILD_DAIRY_BLOCKED_SUCCESSFULLY');
        } else {
            $childUser->is_blocked = 0;
            $childUser->update();
            return $this->childDairyDataReturn($user->user_id, 'CHILD_DAIRY_UNBLOCKED_SUCCESSFULLY');
        }
    }
}
