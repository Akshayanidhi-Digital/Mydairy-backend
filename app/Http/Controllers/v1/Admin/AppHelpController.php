<?php

namespace App\Http\Controllers\v1\Admin;

use App\Helper\Helper;
use App\Models\AppHelp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AppHelpController extends Controller
{
    public function index()
    {
        $title = 'Help';
        $datas = AppHelp::paginate(env('PER_PAGE_RECORDS'));
        return view('admin.app_help.index', compact('title', 'datas'));
    }
    public function create()
    {
        $title = 'Create Help';
        return view('admin.app_help.create', compact('title'));
    }
    public function store(Request $request)
    {
        $request->validate([
            "help_name" => ['required', 'string', "max:200"],
            "help_url" => ['required', 'url'],
            "help_image" => ['required', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);
        $help = new AppHelp();
        $help->name = $request->help_name;
        $help->url = $request->help_url;
        if ($request->hasFile('help_image')) {
            $imgname = Str::random(10) . '.' . $request->help_image->extension();
            $path = storage_path('app/public/help_image');
            $request->help_image->move($path, $imgname);
            $help->image = $imgname;
        } else {
            return redirect()->back()->with('error', 'Please Upload Image for display as thumbnail.');
        }
        $help->save();
        return redirect()->route('admin.apphelp.list')->with('success', 'New help add to help section of application.');
    }
    public function status(Request $request)
    {
        $rules = [
            'id' => ['required', Rule::exists('app_helps')]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $help = AppHelp::find($request->id);
        $help->trash = ($help->trash) ? false : true;
        $help->update();
        return Helper::SuccessReturn(null, 'Status changed successfully.');
    }
    public function delete(Request $request)
    {
        $rules = [
            'id' => ['required', Rule::exists('app_helps')]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        // $help = AppHelp::find($request->id);
        // $help->delete();
        $help = AppHelp::where('id', $request->id)->update(['trash' => 1]);
        return Helper::SuccessReturn(null, 'Help deleted successfully.');
    }
    public function edit($id)
    {
        $help = AppHelp::find($id);
        $title = 'Edit app help';
        if (!$help) {
            return redirect()->route('admin.apphelp.list')->with('error', 'select valid record.');
        }
        return view('admin.app_help.edit', compact('help', 'title'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            "help_name" => ['nullable', 'string', "max:200"],
            "help_url" => ['nullable', 'url'],
            "help_image" => ['nullable', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);
        $help = AppHelp::find($id);
        if (!$help) {
            return redirect()->route('admin.apphelp.list')->with('error', 'select valid record.');
        }
        $help->name = $request->input('help_name', $help->name);
        $help->url = $request->input('help_url', $help->url);
        if ($request->hasFile('help_image')) {
            $imgname = Str::random(10) . '.' . $request->help_image->extension();
            $path = storage_path('app/public/help_image');
            $request->help_image->move($path, $imgname);
            $help->image = $imgname;
        } else {
            $help->image = $help->image;
        }
        $help->trash = false;
        $help->update();
        return redirect()->route('admin.apphelp.list')->with('success', 'Help updated successfully.');
    }
}
