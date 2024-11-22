<?php

namespace App\Http\Controllers\Api\v1;

use App\Helper\Helper;
use App\Models\Transporters;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\IsDairyMiddleware;

class TranspoterController extends Controller
{
    public static function middleware(): array
    {
        return [
            IsDairyMiddleware::class
        ];
    }
    public function index(Request $request)
    {
        $user = $request->user();
        return $this->getList($user->user_id, 'TRANSPORTERS_LIST_FETCHED');
    }
    protected function getList($parent_id, $message)
    {
        $data = Transporters::where(['parent_id' => $parent_id, 'deleted' => false])->get();
        return Helper::StatusReturn($data, $message);
    }
    public function add(Request $request)
    {
        $user = $request->user();
        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'father_name' => ['required', 'string', 'max:150'],
            'country_code' => ['required', 'string'],
            'mobile' => [
                'required',
                'numeric',
                Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                Rule::unique('transporters', 'mobile')->where(function ($query) use ($request) {
                    $query->where('country_code', $request->country_code);
                })
            ],
            'email' => ['nullable', 'email'],
            'transporter_name' => ['required', 'string', 'max:150'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $password = 123456; // rand(1000,9999);
        $trasnporter = new Transporters();
        $trasnporter->parent_id = $user->user_id;
        $trasnporter->name = $request->name;
        $trasnporter->father_name = $request->father_name;
        $trasnporter->country_code = $request->country_code;
        $trasnporter->mobile = $request->mobile;
        $trasnporter->email = $request->input('email', null);
        $trasnporter->transporter_name = $request->transporter_name;
        $trasnporter->password = bcrypt($password);
        $res = $this->sendPasswordMessage($request->country_code, $request->mobile, $password);
        $trasnporter->save();
        return $this->getList($user->user_id, 'TRANSPORTERS_ADDED_SUCCESSFULLY');
    }



    protected function sendPasswordMessage($country_code, $mobile, $password)
    {
        $message = 'Hi, your transporter account ' . $country_code . ' ' . $mobile . ' Password is ' . $password;
        return Helper::SuccessReturn(null, 'Password Send');
    }



    public function update(Request $request)
    {
        $user = $request->user();
    }



    public function statusUpdate(Request $request)
    {
        $user = $request->user();
        $rules = [
            'transporter_id' => ['required', Rule::exists('transporters', 'transporter_id')->where(function ($query) use ($user) {
                $query->where(['parent_id' => $user->user_id, 'deleted' => false]);
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $trasnporter = Transporters::where(['transporter_id' => $request->transporter_id, 'parent_id' => $user->user_id, 'deleted' => false])->first();
        $trasnporter->is_blocked = $trasnporter->is_blocked ? false : true;
        $trasnporter->update();
        // $message = ;
        // return $request;
        return $this->getList($user->user_id, $trasnporter->is_blocked ?  'TRANSPORTERS_BLOCKED_SUCCESSFULLY' : 'TRANSPORTERS_UNBLOCKED_SUCCESSFULLY');
    }



    public function delete(Request $request)
    {
        return $request;
    }
}
