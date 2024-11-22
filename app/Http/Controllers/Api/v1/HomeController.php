<?php

namespace App\Http\Controllers\Api\v1;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Buyers;
use App\Models\DealerRoles;
use App\Models\Farmer;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $rules = [
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $mobile = $request->input('mobile');
        $country_code = $request->input('country_code');
        $muser = User::where(['country_code' => $country_code, 'mobile' => $mobile])->first();
        $farmer = Farmer::where(['country_code' => $country_code, 'mobile' => $mobile])->first();
        $buyer = Buyers::where(['country_code' => $country_code, 'mobile' => $mobile])->first();
        if (!$buyer && !$farmer && !$muser) {
            return Helper::FalseReturn(null, 'USER_ACCOUNT_NOT_FOUND');
        } else {
            $roles = DealerRoles::all();

            // $data = [
            //     'dairy' => ($muser && $muser->role == 0) ? true : false,
            //     'farmer' => ($farmer) ? true : false,
            //     'buyer' => ($buyer) ? true : false,
            // ];
            $data = [];
            if($muser && $muser->role == 0){
                $data['Dairy'] = '1';
            }
            if($farmer){
                $data['Farmer'] = '2';
            }
            if($buyer){
                $data['Buyer'] = '3';
            }
            foreach ($roles as $role) {
                if(($muser && $muser->role_id == $role->role_id)){
                    $data[$role->short_name] = '0';
                }
            }
            return Helper::SuccessReturn($data, 'USER_ACCOUNT_FOUND');
        }
    }

}
