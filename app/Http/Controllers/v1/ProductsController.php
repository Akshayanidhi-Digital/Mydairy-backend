<?php

namespace App\Http\Controllers\v1;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Middleware\SubDairyUserAccess;
use App\Models\Products;
use App\Models\ProductsBrands;
use App\Models\ProductsGroup;
use App\Models\ProductsUnitTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProductsController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(SubDairyUserAccess::class),
        ];
    }
    public function groups()
    {
        $title = __('lang.Products Groups List');
        $user = auth()->user();
        $groups = ProductsGroup::where(['user_id' => $user->user_id,])->paginate(env('PER_PAGE_RECORDS')); //'trash'=>0
        return view('user.products.groups.index', compact('title', 'groups'));
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
            return view('user.products.brands.index', compact('title', 'brands', 'groups'));
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

    public function index()
    {
        $user = auth()->user();
        $products = Products::where(['user_id' => $user->user_id])->with('unitType')->paginate(env('PER_PAGE_RECORDS'));
        $title = __('lang.Products List');
        return view('user.products.index', compact('title', 'products'));
    }
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = auth()->user();
            $request->validate([
                "name" => ["required"],
                "description" => ["required"],
                "group" => ["required", Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                    return $query->where(['user_id' => $user->user_id, 'trash' => 0]);
                })],
                "brand" => ["required", 'string', Rule::exists('products_brands', 'id')->where(function ($query) use ($user, $request) {
                    return $query->where(['user_id' => $user->user_id, 'group' => $request->group, 'trash' => 0]);
                })],
                "unit_type" => ['required', Rule::exists('products_unit_types', 'id')->where(function ($query) {
                    return $query->where(['trash' => 0]);
                })],
                "weight" => ['required', "numeric"],
                "price" => ['required', "numeric"],
                "tax" => ['required', "numeric"],
                "stock" => ['required', "numeric"],
                "image" => ['required', File::types(['png', 'jpg'])->min(100)
                    ->max(2 * 1024)],
            ]);
            $product = new Products();
            $product->user_id = $user->user_id;
            $product->name = $request->name;
            $product->brand = $request->brand;
            $product->group = $request->group;
            $product->unit_type = $request->unit_type;
            $product->price = $request->price;
            $product->is_tax = ($request->tax != null) ? true : false;
            $product->tax = $request->input('tax', 0);
            $product->is_weight = ($request->weight != null) ? true : false;
            $product->weight = $request->input('weight', 0);
            $product->stock = $request->input('stock', 0);
            $product->desciption = $request->description;
            if ($request->hasFile('image')) {
                $imgname = Carbon::now()->timestamp . Str::random(10) . '.' . $request->image->extension();
                $path = storage_path('app/public/' . $user->user_id . '/products');
                $request->image->move($path, $imgname);
                $product->image = $imgname;
            }
            $product->save();
            return redirect()->route('user.products.list')->with('success', 'New product added.');
        } else {
            $title = __('lang.Add :name', ['name' => __('lang.Product')]);
            $user = auth()->user();
            $groups = ProductsGroup::where(['user_id' => $user->user_id, 'trash' => 0])->get();
            $units = ProductsUnitTypes::where(['trash' => 0])->get();
            return view('user.products.add', compact('title', 'groups', 'units'));
        }
    }
    public function edit($id, Request $request)
    {
        $user = auth()->user();
        $product = Products::where(['id' => $id, 'user_id' => $user->user_id])->first();
        if (!$product) {
            return redirect()->route('user.products.list')->with('error', __('message.PRODUCTS_NOT_FOUND'));
        }
        if ($request->isMethod('post')) {
            $request->validate([
                "name" => ["nullable", "string"],
                "description" => ["nullable", "string"],
                "group" => ["nullable", Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                    return $query->where(['user_id' => $user->user_id, 'trash' => 0]);
                })],
                "brand" => ["nullable", 'string', Rule::exists('products_brands', 'id')->where(function ($query) use ($user, $request) {
                    return $query->where(['user_id' => $user->user_id, 'group' => $request->group, 'trash' => 0]);
                })],
                "unit_type" => ['nullable', Rule::exists('products_unit_types', 'id')->where(function ($query) {
                    return $query->where(['trash' => 0]);
                })],
                "weight" => ['nullable', 'numeric'],
                "price" => ['nullable', 'numeric'],
                "tax" => ['nullable', 'numeric'],
                "stock" => ['nullable', 'numeric'],
                "image" => ['nullable', File::types(['png', 'jpg'])->min(100)
                    ->max(2 * 1024)],
            ]);
            $product->name = $request->input('name', $product->name);
            $product->brand = $request->input('brand', $product->brand);
            $product->group = $request->input('group', $product->group);
            $product->unit_type = $request->input('unit_type', $product->unit_type);
            $product->price = $request->input('price', $product->price);
            $product->is_tax = ($request->input('tax', $product->tax) != null) ? true : false;
            $product->tax = $request->input('tax', 0);
            $product->is_weight = ($request->input('weight', $product->weight) != null) ? true : false;
            $product->weight = $request->input('weight', $product->weight);
            $product->stock = $request->input('stock', $product->stock);
            $product->desciption = $request->input('description', $product->description);
            if ($request->hasFile('image')) {
                $imgname = Carbon::now()->timestamp . Str::random(10) . '.' . $request->image->extension();
                $path = storage_path('app/public/' . $user->user_id . '/products');
                $request->image->move($path, $imgname);
                $product->image = $imgname;
            }
            $product->update();
            return redirect()->route('user.products.list')->with('success', 'In House product updated successfully.');
        } else {
            $title = __('lang.:name Edit', ['name' => __('lang.Product')]);
            $groups = ProductsGroup::where(['user_id' => $user->user_id, 'trash' => 0])->get();
            $units = ProductsUnitTypes::where(['trash' => 0])->get();
            return view('user.products.edit', compact('title', 'product', 'groups', 'units'));
        }
    }
    public function delete(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'id' => ['required', Rule::exists('products', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $product = Products::where(['id' => $request->id, 'user_id' => $user->user_id])->first();
        $product->delete();
        return Helper::SuccessReturn(null, 'Product deleted successfully.');
    }
}
