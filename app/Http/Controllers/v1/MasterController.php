<?php

namespace App\Http\Controllers\v1;

use App\Models\User;
use App\Helper\Helper;
use App\Models\Pakeage;
use App\Models\ChildDairy;
use App\Models\DealerRoles;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\DealerRolePermissions;
use App\Models\DealerPermissionAllowed;
use App\Http\Middleware\IsDairyMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MasterController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(IsDairyMiddleware::class),
        ];
    }

    public function index()
    {
        $title = __('lang.Master Management');
        return view('user.masters.index', compact('title'));
    }

    public function roleList()
    {
        $title = __('lang.Role Management');
        $roles = DealerRoles::all();
        return view('user.masters.role.index', compact('title', 'roles'));
    }

    public function rolePermissonView($role_id)
    {
        $title = __('lang.Role Permission Management');
        $role = DealerRoles::where('role_id', $role_id)->first();
        if (!$role) {
            return redirect()->route('user.masters.roles.list')->with('error', __('message.ROLE_NOT_FOUND'));
        }
        $user = auth()->user();
        $permissionIds = DealerRolePermissions::pluck('permission_id');
        $tempermission = DealerPermissionAllowed::where(['user_id' => $user->user_id, 'role_id' => $role_id])->whereIn('permission_id', $permissionIds)->get();
        if ($tempermission->count() != $permissionIds->count()) {
            foreach ($permissionIds as $key => $value) {
                DealerPermissionAllowed::updateOrcreate(
                    ['user_id' => $user->user_id, 'role_id' => $role_id, 'permission_id' => $value],
                );
            }
            $permissions =  DealerPermissionAllowed::where(['user_id' => $user->user_id, 'role_id' => $role_id])->whereIn('permission_id', $permissionIds)->get();
        } else {
            $permissions = $tempermission;
        }
        $groupedPermissions = $permissions->groupBy(function ($item) {
            return explode('.', $item->permission_name)[0];
        });
        // return $groupedPermissions;
        // $tem2 = $permissions->pluck('permission_name');
        // foreach ($tem2 as $permission) {
        //     $parts = explode('.', $permission);
        //     $groupKey = $parts[0];
        //     $groupedPermissions[$groupKey][] = $permission;
        // }
        return view('user.masters.role.view', compact('title', 'role', 'permissions', 'groupedPermissions'));
    }
    public function rolePermissonUpdate(Request $request, $role_id)
    {
        // return $request;
        $user = auth()->user();
        $data = $request->except('_token');
        $formattedPermissions = [];
        foreach ($data as $key => $value) {
            $formattedKey = str_replace('_', '.', $key);
            // echo $formattedKey . "<br>";
            $formattedPermissions[] = $formattedKey;
            $temp = DealerRolePermissions::where('name', '=', $formattedKey)->first();
            if ($temp) {
                DealerPermissionAllowed::where([
                    "user_id" => $user->user_id,
                    'permission_id' => $temp->permission_id,
                    'role_id' => $role_id,
                ])->update(['access' => 1]);
            }
        }
        $permissionIds = DealerRolePermissions::whereNotIn('name', $formattedPermissions)->get()->pluck('permission_id');
        $permissionIds->count();
        // die('************' . $permissionIds->count());
        if ($permissionIds->count() > 0) {
            DealerPermissionAllowed::where([
                "user_id" => $user->user_id,
                'role_id' => $role_id,
            ])->whereIn('permission_id', $permissionIds)->update(['access' => 0]);
        }
        // else{
        //   return      DealerPermissionAllowed::where([
        //             "user_id" => $user->user_id,
        //             'role_id' => $role_id,
        //         ])->whereIn('permission_id', $permissionIds)->get();
        // }
        return redirect()->route('user.masters.roles.view', $role_id)->with('success', __('message.ROLE_PERMISSION_UPDATED'));
    }
    public function childUser($role_type)
    {
        $title = __('lang.:name Management', ['name' => $role_type]);
        $user = auth()->user();
        $role_id = DealerRoles::where('short_name', $role_type)->first();
        if (!$role_id) {
            return redirect()->route('user.dashboard')->with('error', __('message.INVALID_REQUEST'));
        }
        $users =  User::where(['parent_id' => $user->user_id, 'role_id' => $role_id->role_id])->with('profile')->get();
        return view('user.costumers.child.index', compact('title', 'users', 'role_type'));
    }

    public function childUserStatusUpdate(Request $request)
    {
        $user = auth()->user();
        $dairy = User::where(['user_id' => $request->dairy_id, 'parent_id' => $user->user_id])->first();
        if (!$dairy) {
            return Helper::FalseReturn([], 'CHILD_DAIRY_NOT_FOUND');
        }

        $role = DealerRoles::where('role_id', $dairy->role_id)->first()->short_name;
        if ($dairy->is_blocked == 0) {
            $dairy->is_blocked = 1;
            $dairy->save();
            return Helper::SuccessReturn([], 'CHILD_DAIRY_BLOCKED_SUCCESSFULLY', ['name' => $role]);
        } else {
            $dairy->is_blocked = 0;
            $dairy->save();
            return Helper::SuccessReturn([], 'CHILD_DAIRY_UNBLOCKED_SUCCESSFULLY', ['name' => $role]);
        }
    }
    private function childUserLimitCheck($role)
    {
        $user_count = Pakeage::where('plan_id', auth()->user()->plan_id)->first()->user_count;
        $count = User::where('role_id', $role)->count();
        if ($user_count == 0  || $user_count <= $count) {
            return redirect()->route('user.dashboard')->with('error', __('message.USER_LIMIT_REACHED'));
        }
    }
    public function childUserAdd($role_type)
    {
        $this->childUserLimitCheck($role_type);
        // if
        $title = __('lang.Add New :name', ['name' => $role_type]);
        $roles = DealerRoles::all();
        return view('user.costumers.child.add', compact('title', 'roles', 'role_type'));
    }

    public function childUserStore($role_type, Request $request)
    {
        $this->childUserLimitCheck($role_type);
        $request->validate([
            "name" => ['required', 'string', "max:100"],
            "father_name" => ['required', 'string', "max:100"],
            "email" => ['nullable', 'email', "unique:users,email"],
            "mobile" => ['required', "numeric", "unique:users,mobile"],
            "role" => ['required'],
            "dairy_name" => ['required'],
            "address" => ['required', "regex:/((\d{1,2}\/\d{1,2} [A-Za-z0-9\s]+)?|([A-Za-z0-9\s]+)?)(, [A-Za-z]+)?(, [A-Z]{2})?(, \d{5,6})?$/"],
            "latitude" => ['nullable', "numeric"],
            "longitude" => ['nullable', "numeric"],
        ]);
        $dairy = new User();
        $dairy->parent_id = auth()->user()->user_id;
        $dairy->name = $request->name;
        $dairy->father_name = $request->father_name;
        $dairy->email = (isset($request->email)) ? $request->email : null;
        $dairy->mobile = $request->mobile;
        $dairy->role_id = $request->role;
        $dairy->password = bcrypt(123456); // send notification password
        $dairy->role = 2;
        $dairy->save();
        $dairy_profile = new UserProfile();
        $dairy_profile->user_id = $dairy->user_id;
        $dairy_profile->dairy_name = $request->dairy_name;
        $dairy_profile->address = $request->address;
        $dairy_profile->latitude = $request->latitude;
        $dairy_profile->longitude = $request->longitude;
        $dairy_profile->save();
        return redirect()->route('user.childUser.list', $role_type)->with('success', __('message.CHILD_DAIRY_ADD', ['name' => $role_type]));
    }
    public function childUserEdit($dairy_id)
    {
        $user = auth()->user();
        $dairy = User::where(['user_id' => $dairy_id, 'parent_id' => $user->user_id])->with('profile')->first();
        if (!$dairy) {
            return redirect()->route('user.childUser.list')->with('error', __('message.CHILD_DAIRY_NOT_FOUND', ['name' => $dairy->role_name]));
        }
        $title = __('lang.:name Edit', ['name' => $dairy->role_name]);
        $roles = DealerRoles::all();
        return view('user.costumers.child.edit', compact('title', 'roles', 'dairy'));
    }
    public function childUserUpdate($dairy_id, Request $request)
    {
        $user = auth()->user();
        $dairy = User::where(['user_id' => $dairy_id, 'parent_id' => $user->user_id])->with('profile')->first();
        if (!$dairy) {
            return redirect()->route('user.childUser.list')->with('error', __('message.CHILD_DAIRY_NOT_FOUND', ['name' => $dairy->role_name]));
        }
        $request->validate([
            "name" => ['required', 'string', "max:100"],
            "father_name" => ['required', 'string', "max:100"],
            "email" => ['nullable', 'email', Rule::unique('users', 'email')->ignore($dairy_id, 'user_id')],
            "mobile" => ['required', "numeric", Rule::unique('users', 'mobile')->ignore($dairy_id, 'user_id')],
            "role" => ['required'],
            "dairy_name" => ['required'],
            "address" => ['required', "string"],
            "latitude" => ['nullable', "numeric"],
            "longitude" => ['nullable', "numeric"],
        ]);
        $dairy->name = $request->name;
        $dairy->father_name = $request->input('father_name', $dairy->father_name);
        $dairy->email = (isset($request->email)) ? $request->email : null;
        $dairy->mobile = $request->mobile;
        $dairy->role_id = $request->role;
        $dairy->update();
        $dairy_profile = UserProfile::where('user_id', '=', $dairy_id)->first();
        $dairy_profile->dairy_name = $request->dairy_name;
        $dairy_profile->address = $request->address;
        $dairy_profile->latitude = $request->latitude;
        $dairy_profile->longitude = $request->longitude;
        $dairy_profile->update();
        $role = DealerRoles::where('role_id', $dairy->role_id)->first()->short_name;
        return redirect()->route('user.childUser.list', $role)->with('success', __('message.CHILD_DAIRY_UPDATE', ['name' => $role]));
    }
    public function childUserView($dairy_id)
    {
        return " I Am user View";
    }
    public function rolePermission()
    {
        return "i am permission.";
    }
}
