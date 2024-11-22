<?php

namespace App\Http\Controllers\v1;

use App\Helper\Helper;
use App\Models\Routes;
use App\Models\Transporters;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
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
        $user = auth()->user();
        $datas = Transporters::where(['parent_id' => $user->user_id, 'deleted' => false])->orderby('transporter_id', 'desc')->paginate(env('PER_PAGE_RECORDS'));
        $title = __('lang.:name Management',['name'=> __('lang.Transporter')]);
        return view('user.masters.transport.index', compact('title', 'datas'));
    }
    public function statusUpdate(Request $request)
    {
        $user = auth()->user();
        $childUser = Transporters::where(['transporter_id' => $request->transporter_id, 'parent_id' => $user->user_id])->first();
        if (!$childUser) {
            return Helper::FalseReturn([], 'TRANSPORT_NOT_FOUND');
        }
        if ($childUser->is_blocked) {
            $childUser->is_blocked  = false;
            $childUser->update();
            return Helper::SuccessReturn([], 'TRANSPORT_UNBLOCKED_SUCCESSFULLY');
        } else {
            $childUser->is_blocked = true;
            $childUser->update();
            return Helper::SuccessReturn([], 'TRANSPORT_BLOCKED_SUCCESSFULLY');
        }
    }
    public function add()
    {
        $title = __('lang.Add :name', ['name' => __('lang.Transporter')]);
        return view('user.masters.transport.add', compact('title'));
    }
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            "father_name" => ['required', 'string', 'max:150'],
            "transport_name" => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email'],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('transporters', 'mobile')],
        ]);

        $tp = new Transporters();
        $tp->name = $request->name;
        $tp->father_name = $request->father_name;
        $tp->transporter_name = $request->transport_name;
        $tp->country_code = $request->country_code;
        $tp->mobile = $request->mobile;
        $tp->email = $request->input('email', null);
        $tp->parent_id = $user->user_id;
        $tp->password = bcrypt(123456); // generate auto passowrd and send text message.
        $tp->save();
        return redirect()->route('user.masters.transport.list')->with('success', __('message.TRANSPORT_ADDED_SUCCESSFULLY'));
    }
    public function edit($transporter_id)
    {
        $title = __('lang.:name Edit', ['name' => __('lang.Transporter')]);
        $user = request()->user();
        $data = Transporters::where(['transporter_id' => $transporter_id, 'parent_id' => $user->user_id, 'deleted' => false])->first();
        if (!$data) {
            return redirect()->route('user.masters.transport.list')->with('error', __('message.TRANSPORT_NOT_FOUND'));
        }
        return view('user.masters.transport.edit', compact('title', 'data'));
    }
    public function update(Request $request, $transporter_id)
    {
        $user = request()->user();
        $tp = Transporters::where(['transporter_id' => $transporter_id, 'parent_id' => $user->user_id, 'deleted' => false])->first();
        if (!$tp) {
            return redirect()->route('user.masters.transport.list')->with('error', __('message.TRANSPORT_NOT_FOUND'));
        }
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            "father_name" => ['required', 'string', 'max:150'],
            "transport_name" => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email'],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('transporters', 'mobile')->ignore($transporter_id, 'transporter_id')],
        ]);
        $tp->name = $request->input('name', $tp->name);
        $tp->father_name = $request->input('father_name', $tp->father_name);
        $tp->transporter_name = $request->input("transport_name", $tp->transporter_name);
        $tp->country_code = $request->input('country_code', $tp->country_code);
        $tp->mobile = $request->input('mobile', $tp->mobile);
        $tp->email = $request->input('email', $tp->email);
        $tp->update();
        return redirect()->route('user.masters.transport.list')->with('success', __('message.TRANSPORT_UPDATED_SUCCESSFULLY'));
    }
}
