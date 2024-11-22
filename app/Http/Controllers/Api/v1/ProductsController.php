<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Helper\Helper;
use App\Models\Products;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductsGroup;
use App\Models\ProductsBrands;
use Illuminate\Validation\Rule;
use App\Models\ProductsUnitTypes;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $products = Products::where(['user_id' => $user->user_id, 'trash' => 0])->get();
        return Helper::SuccessReturn($products, 'PRODUCTS_LIST');
    }
    public function addProduct(Request $request)
    {
        $user = $request->user();
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'group' => ['required', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id, 'trash' => 0]);
            })],
            'brand' => ['required', 'string', Rule::exists('products_brands', 'id')->where(function ($query) use ($user, $request) {
                return $query->where(['user_id' => $user->user_id, 'group' => $request->group, 'trash' => 0]);
            })],
            'unit_type' => ['required', Rule::exists('products_unit_types', 'id')->where(function ($query) {
                return $query->where(['trash' => 0]);
            })],
            'price' => ['required', 'numeric'],
            // 'unit_type'=>['required','string','max:100'],
            'is_weight' => ['nullable', 'in:0,1'],
            'weight' => [Rule::requiredIf(function () use ($request) {
                return $request->is_weight == 1;
            }), 'numeric'],
            'is_tax' => ['nullable', 'in:0,1'],
            'tax' => [Rule::requiredIf(function () use ($request) {
                return $request->is_tax == 1;
            }), 'numeric'],
            'stock' => ['required', 'numeric'],
            'image' => ['required', File::types(['png', 'jpg'])->min(100)
                ->max(2 * 1024)],
            'desciption' => ['required', 'string', 'min:100', "max:220"],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $product = new Products();
        // 'user_id', 'brand', 'group', 'name', 'desciption', 'image', 'unit_type', 'price', 'is_tax', 'tax', 'is_weight', 'weight', 'stock', 'trash'

        $product->user_id = $user->user_id;
        $product->name = $request->name;
        $product->brand = $request->brand;
        $product->group = $request->group;
        $product->unit_type = $request->unit_type;
        $product->price = $request->price;
        $product->is_tax = ($request->is_tax) ? $request->is_tax : 0;
        $product->tax = ($request->tax) ? $request->tax : 0;
        $product->is_weight = ($request->is_weight) ? $request->is_weight : 0;
        $product->weight = ($request->weight) ? $request->weight : 0;
        $product->stock = ($request->stock) ? $request->stock : 0;
        $product->desciption = $request->desciption;
        if ($request->hasFile('image')) {
            $imgname = Carbon::now()->timestamp . Str::random(10) . '.' . $request->image->extension();
            $path = storage_path('app/public/' . $user->user_id . '/products');
            $request->image->move($path, $imgname);
            $product->image = $imgname;
        }
        $product->save();
        return Helper::SuccessReturn(null, 'PRODUCTS_ADD');
    }
    public function editProduct(Request $request)
    {
        $user = $request->user();
        $rules = [
            "product_id" => ['required', Rule::exists('products', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id, 'trash' => 0]);
            })],
            'name' => ['nullable', 'string', 'max:100'],
            'group' => ['nullable', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id, 'trash' => 0]);
            })],
            'brand' => ['nullable', 'string', Rule::exists('products_brands', 'id')->where(function ($query) use ($user, $request) {
                return $query->where(['user_id' => $user->user_id, 'group' => $request->group, 'trash' => 0]);
            })],
            'unit_type' => ['nullable', Rule::exists('products_unit_types', 'id')->where(function ($query) {
                return $query->where(['trash' => 0]);
            })],
            'price' => ['nullable', 'numeric'],
            // 'unit_type'=>['required','string','max:100'],
            'is_weight' => ['nullable', 'in:0,1'],
            'weight' => [Rule::requiredIf(function () use ($request) {
                return $request->is_weight == 1;
            }), 'numeric'],
            'is_tax' => ['nullable', 'in:0,1'],
            'tax' => [Rule::requiredIf(function () use ($request) {
                return $request->is_tax == 1;
            }), 'numeric'],
            'stock' => ['nullable', 'numeric'],
            'image' => ['nullable', File::types(['png', 'jpg'])->min(100)
                ->max(2 * 1024)],
            'desciption' => ['nullable', 'string', 'min:100', "max:220"],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $product = Products::where(['id' => $request->product_id, 'user_id' => $user->user_id])->first();
        $product->name = $request->input('name', $product->name);
        $product->brand = $request->input('brand', $product->brand);
        $product->group = $request->input('group', $product->group);
        $product->unit_type = $request->input('unit_type', $product->unit_type);
        $product->price = $request->input('price', $product->price);
        $product->is_tax = $request->input('is_tax', $product->is_tax);
        $product->tax = $request->input('tax', $product->tax);
        $product->is_weight = $request->input('is_weight', $product->is_weight);
        $product->weight = $request->input('is_weight', $product->weight);
        $product->stock = $request->input('stock', $product->stock);
        $product->desciption = $request->input('desciption', $product->desciption);
        if ($request->hasFile('image')) {
            $imgname = Carbon::now()->timestamp . Str::random(10) . '.' . $request->image->extension();
            $path = storage_path('app/public/' . $user->user_id . '/products');
            $request->image->move($path, $imgname);
            $product->image = $imgname;
        }
        $product->update();
        return Helper::SuccessReturn(null, 'PRODUCTS_UPDATED');
    }
    public function addProductGroup(Request $request)
    {
        $user = $request->user();
        $rules = [
            'group_name' => ['required', 'string', Rule::unique('products_groups', 'group')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = new ProductsGroup();
        $data->user_id = $user->user_id;
        $data->group = $request->group_name;
        $data->save();
        return Helper::SuccessReturn(null, 'PRODUCT_GROUP_ADDED');
    }
    public function editProductGroup(Request $request)
    {
        $user = $request->user();
        $rules = [
            'id' => ['required', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
            'group_name' => ['required', 'string', Rule::unique('products_groups', 'group')->ignore($request->id)->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = ProductsGroup::where(['id' => $request->id, 'user_id' => $user->user_id])->first();
        $data->group = $request->group_name;
        $data->update();
        return Helper::SuccessReturn(null, 'PRODUCT_GROUP_UPDATED');
    }
    public function updateProductGroup(Request $request)
    {
        $user = $request->user();
        $rules = [
            'id' => ['required', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = ProductsGroup::where(['id' => $request->id, 'user_id' => $user->user_id])->first();
        $data->trash = ($data->trash == 0) ? 1 : 0;
        $data->update();
        return Helper::SuccessReturn(null, 'PRODUCT_GROUP_DELETED');
    }
    public function productsUnitTypes(Request $request)
    {
        $data = ProductsUnitTypes::where(['trash' => 0])->get();
        return Helper::SuccessReturn($data, 'PRODUCTS_UNIT_TYPES');
    }
    public function listProductGroup(Request $request)
    {
        $user = $request->user();
        $data = ProductsGroup::where(['user_id' => $user->user_id])->get();
        return Helper::SuccessReturn($data, 'PRODUCTS_GROUP_LIST');
    }
    public function addProductBrand(Request $request)
    {
        $user = $request->user();
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
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = new ProductsBrands();
        $data->user_id = $user->user_id;
        $data->group = $request->group;
        $data->brand = $request->brand_name;
        $data->save();
        return Helper::SuccessReturn(null, 'PRODUCT_BRAND_ADDED');
    }
    public function editProductBrand(Request $request)
    {
        $user = $request->user();
        $rules = [
            'id' => ['required', Rule::exists('products_brands', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
            'group' => ['required', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
            'brand_name' => ['required', 'string', Rule::unique('products_brands', 'brand')->ignore($request->id)->where(function ($query) use ($user, $request) {
                return $query->where(['user_id' => $user->user_id, 'group' => $request->group]);
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = ProductsBrands::where(['id' => $request->id, 'user_id' => $user->user_id])->first();
        $data->group = $request->input('group', $data->group);
        $data->brand = $request->input('brand_name', $data->brand);
        $data->update();
        return Helper::SuccessReturn(null, 'PRODUCT_BRAND_UPDATED');
    }
    public function updateProductBrand(Request $request)
    {
        $user = $request->user();
        $rules = [
            'id' => ['required', Rule::exists('products_brands', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = ProductsBrands::where(['id' => $request->id, 'user_id' => $user->user_id])->first();
        $data->trash = ($data->trash == 0) ? 1 : 0;
        $data->update();
        return Helper::SuccessReturn(null, 'PRODUCT_GROUP_DELETED');
    }
    public function groupListProductBrand(Request $request)
    {
        $user = $request->user();
        $data = ProductsGroup::where(['user_id' => $user->user_id, 'trash' => false])->select('id', 'group as name')->get();
        return Helper::SuccessReturn($data, 'PRODUCTS_GROUP_LIST');
    }
    public function listProductBrand(Request $request)
    {
        $user = $request->user();
        $rules = [
            'group' => ['nullable', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = ProductsBrands::query();
        $data = $data->where(['user_id' => $user->user_id]);
        if (isset($request->group)) {
            $data = $data->where('group', $request->group);
        }
        $data = $data->get();
        return Helper::SuccessReturn($data, 'PRODUCTS_BRAND_LIST');
    }
}
