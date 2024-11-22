<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Route;
// use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_id',
        'parent_id',
        'country_code',
        'mobile',
        'is_email_verified',
        'is_blocked',
        'is_verified',
        'role',
        'user_type',
        'plan_id',
        'plan_created',
        'fcm_token',
        'plan_expired',
    ];
    protected $hidden = [
        'password',
        'remember_token',
        "is_email_verified",
        "role",
        "role_id",
        "user_type",
        'fcm_token',
        'deleted_at',
    ];
    public static function updateRouteID($user_id, $route_id)
    {
        // return self::where('user_id', $user_id)->update(['route_id' => $route_id]);
        return self::where('user_id', $user_id);
    }
    public static function getRouteID($user_id)
    {
        return self::where('user_id', $user_id)->value('route_id');
    }

    public function is_admin()
    {
        return ($this->role == 1) ? true : false;
    }
    public function is_subdairy()
    {
        return ($this->role == 2) ? true : false;
    }
    public function costumers()
    {
        $user_types =  array(
            [
                'user_type' => 'FAR',
                'name' => 'Farmer'
            ],
            [
                'user_type' => 'BYR',
                'name' => 'Buyer'
            ],
        );
        if ($this->role == 2) {
            return $user_types;
        }
        $muserData = DealerRoles::all()->map(function ($muser) {
            return [
                'user_type' => $muser->role_id,
                'name' => $muser->short_name,
            ];
        })->toArray();
        return array_merge($user_types, $muserData);
    }
    public function is_permission()
    {
        $currentRouteName = Route::currentRouteName();
        if (strpos($currentRouteName, 'user.') === 0) {
            $currentRouteName = substr($currentRouteName, 5);
        }
        $permission = DealerRolePermissions::where('name', $currentRouteName)->first();
        if ($permission) {
            return  DealerPermissionAllowed::where([
                'user_id' => $this->parent_id,
                "role_id" => $this->role_id,
                'permission_id' => $permission->permission_id,
                'access' => true,
            ])->exists();
        } else {
            return false;
        }
        return ($this->role == 0) ? true : false;
    }
    public function is_permission_Route($currentRouteName)
    {
        $permission = DealerRolePermissions::where('name', $currentRouteName)->first();
        if ($permission) {
            return  DealerPermissionAllowed::where([
                'user_id' => $this->parent_id,
                "role_id" => $this->role_id,
                'permission_id' => $permission->permission_id,
                'access' => true,
            ])->exists();
        } else {
            return false;
        }
    }
    public function is_single()
    {
        return ($this->user_type == 0) ? true : false;
    }
    public function is_blocked()
    {
        return ($this->is_blocked == 1) ? true : false;
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $lastUser = static::latest('user_id')->where('role', $user->role)->first();

            if ($user->role == 1) {
                if ($lastUser) {
                    $lastNumber = (int)substr($lastUser->user_id, strlen('ADMIN_'));
                    $userCode = $lastNumber + 1;
                } else {
                    $userCode = 001;
                }
                $userId = 'ADMIN_' . str_pad($userCode, 3, '0', STR_PAD_LEFT);
            } else if ($user->role == 2) {
                if ($lastUser) {
                    $lastNumber = (int)substr($lastUser->user_id, strlen('MYDAIRYSUB_'));
                    $userCode = $lastNumber + 1;
                } else {
                    $userCode = 001;
                }
                $userId = 'MYDAIRYSUB_' . str_pad($userCode, 3, '0', STR_PAD_LEFT);
            } else {
                if ($lastUser) {
                    $lastNumber = (int)substr($lastUser->user_id, strlen('MYDAIRY_'));
                    $userCode = $lastNumber + 1;
                } else {
                    $userCode = 001;
                }
                $userId = 'MYDAIRY_' . str_pad($userCode, 3, '0', STR_PAD_LEFT);
            }
            $user->user_id = $userId;
        });
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'user_id');
    }
    public function isProfile()
    {
        return $this->profile()->exists();
    }
    public function isPlanExpired()
    {
        if ($this->is_subdairy()) {
            $user = User::where('user_id', $this->parent_id)->first();
            if ($user) {
                return $user->planexpired();
            } else {
                return true;
            }
        } else {
            return $this->planexpired();
        }
    }
    public function planexpired()
    {
        if (isset($this->plan_expired)) {
            $time = Carbon::parse($this->plan_expired);
            if (Carbon::now()->floatDiffInDays($time) < 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
    protected static function booted()
    {
        static::retrieved(function ($user) {
            if ($user->hasAllAttributes(['role', 'role_id'])) {
                // $user->role_name = ($user->role == 2) ? DealerRoles::where('role_id', $user->role_id)->first()->short_name : "NA";
            }
        });
        static::updating(function ($user) {
            unset($user->role_name);
        });
    }
    public function hasAllAttributes(array $attributes)
    {
        foreach ($attributes as $attribute) {
            if (!array_key_exists($attribute, $this->attributes)) {
                return false;
            }
        }
        return true;
    }
    public function notification()
    {
        return MessagesAlert::where(['user_id' => $this->user_id, 'is_marked' => false])
            ->orderby('id', 'desc')
            ->select(
                'message',
                'message_type',
                'record_id'
            )->take(5)->get();
    }


    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
}
