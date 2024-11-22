<?php

namespace App\Http\Controllers\v1\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\ProductsBrands;
use App\Models\ProductsGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function groups()
    {
        $title = __('lang.Products Groups List');
        $user = auth()->user();
        $groups = ProductsGroup::where(['user_id' => $user->user_id,])->paginate(env('PER_PAGE_RECORDS')); //'trash'=>0
        return view('admin.products.groups.index', compact('title', 'groups'));
    }

    public function groupsAdd(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'group_name' => ['required', 'string', Rule::unique('products_groups', 'group')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $data = new ProductsGroup();
        $data->user_id = $user->user_id;
        $data->group = $request->group_name;
        $data->save();
        return Helper::SuccessReturn([], 'PRODUCT_GROUP_ADDED');
    }
    public function groupsUpdate(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'id' => ['required', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
            'group_name' => ['required', 'string', Rule::unique('products_groups', 'group')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })->ignore($request->id)]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $data = ProductsGroup::where(['id' => $request->id, 'user_id' => $user->user_id])->first();
        $data->group = $request->group_name;
        $data->save();
        return Helper::SuccessReturn([], 'PRODUCT_GROUP_UPDATED');
    }
    public function groupstatus(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'id' => ['required', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $data = ProductsGroup::where(['id' => $request->id, 'user_id' => $user->user_id])->first();
        $data->trash = ($data->trash == 0) ? 1 : 0;
        $data->update();
        return Helper::SuccessReturn([], 'PRODUCT_GROUP_DELETED');
    }


    public function brands(Request $request)
    {
        $user = auth()->user();
        if ($request->isMethod('post')) {

            $rules = [
                'group' => ['nullable', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                    return $query->where(['user_id' => $user->user_id]);
                })],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Helper::FalseReturn(null, $validator->errors()->first());
            }
            $brands = ProductsBrands::where(['group' => $request->group, 'user_id' => $user->user_id, 'trash' => 0])->get(); //,'trash'=>0
            return  Helper::SuccessReturn($brands, 'Data fatched.');
        } else {
            $title = __('lang.Product Brands List');
            $groups = ProductsGroup::where(['user_id' => $user->user_id, 'trash' => 0])->paginate(env('PER_PAGE_RECORDS'));
            $brands = ProductsBrands::where(['user_id' => $user->user_id])->paginate(env('PER_PAGE_RECORDS')); //,'trash'=>0
            return view('admin.products.brands.index', compact('title', 'brands', 'groups'));
        }
    }
    public function brandsAdd(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'group' => ['required', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
            'brand_name' => ['required', 'string', Rule::unique('products_brands', 'brand')->where(function ($query) use ($user, $request) {
                return $query->where(['user_id' => $user->user_id, 'group' => $request->group]);
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $data = new ProductsBrands();
        $data->user_id = $user->user_id;
        $data->group = $request->group;
        $data->brand = $request->brand_name;
        $data->save();
        return Helper::SuccessReturn([], 'PRODUCT_GROUP_ADDED');
    }
    public function brandsUpdate(Request $request)
    {
        // return $request;

        $user = auth()->user();
        $rules = [
            'id' => ['required', Rule::exists('products_brands', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
            'group' => ['required', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
            'brand_name' => ['required', 'string', Rule::unique('products_brands', 'brand')->where(function ($query) use ($user, $request) {
                return $query->where(['user_id' => $user->user_id, 'group' => $request->group]);
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $data = ProductsBrands::where(['id' => $request->id, 'user_id' => $user->user_id])->first();
        $data->group = $request->group;
        $data->brand = $request->brand_name;
        $data->save();
        return Helper::SuccessReturn([], 'PRODUCT_GROUP_UPDATED');
    }
    public function brandsStatus(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'id' => ['required', Rule::exists('products_brands', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $data = ProductsBrands::where(['id' => $request->id, 'user_id' => $user->user_id])->first();
        $data->trash = ($data->trash == 0) ? 1 : 0;
        $data->update();
        return Helper::SuccessReturn([], 'PRODUCT_GROUP_Deleted');
    }
}
