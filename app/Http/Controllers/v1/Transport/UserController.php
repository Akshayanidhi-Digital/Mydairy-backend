<?php

namespace App\Http\Controllers\v1\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $title = __('constants.Profile');
        $user = auth()->user('transporters');
        return view('transport.profile.index', compact('title', 'user'));
    }
    public function edit()
    {
        $title = __('lang.:name Edit', ['name' => __('constants.Profile')]);
        $user = auth()->user();
        return view('transport.profile.edit', compact('title', 'user'));
    }
    public function updatePassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'old_password' => ['required'],
                "password" => ['required', "min:6"],
                "confirm_password" => ['required', 'min:6', 'same:password'],
            ]);
            $user = auth()->user();
            if (!Hash::check($request->input('old_password'), $user->password)) {
                return redirect()->back()->with('error', __('message.OLD_PASSWORD_MISMATCH'));
            }
            $user->password = bcrypt($request->password);
            $user->update();
            return redirect()->route('transport.profile.index')->with('success', 'Password Updated successfully.');
        } else {
            $title = 'Password Update';
            return view('transport.profile.password', compact('title'));
        }
    }
    public function update(Request $request)
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
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('transporters', 'mobile')->ignore($user->transporter_id, 'transporter_id')],
        ]);
        $user->name = $request->input('name', $user->name);
        $user->father_name = $request->input('father_name', $user->father_name);
        $user->transporter_name = $request->input("transport_name", $user->transporter_name);
        $user->country_code = $request->input('country_code', $user->country_code);
        $user->mobile = $request->input('mobile', $user->mobile);
        $user->email = $request->input('email', $user->email);
        $user->update();
        return redirect()->route('transport.profile.index')->with('success', 'Profile Updated successfully.');
    }
}
