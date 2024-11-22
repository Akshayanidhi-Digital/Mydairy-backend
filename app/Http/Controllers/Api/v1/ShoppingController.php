<?php

namespace App\Http\Controllers\Api\v1;

use App\Helper\Helper;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\ProductsGroup;
use App\Models\ProductsBrands;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Orders;
use Illuminate\Support\Facades\Validator;

class ShoppingController extends Controller
{
    public function shoppingProducts(Request $request)
    {
        $user = $request->user();
        $rules = [
            'group' => ['nullable', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
            'brand' => ['nullable', Rule::unique('products_brands', 'brand')->where(function ($query) use ($user, $request) {
                return $query->where(['user_id' => $user->user_id, 'group' => $request->group]);
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $products = Products::query();
        // $products = $products->whereNot('user_id','=',$user->user_id);
        $products = $products->where('trash', '=', 0);
        if (isset($request->group)) {
            $products = $products->where('group', '=', $request->group);
        }
        if (isset($request->brand)) {
            $products = $products->where('brand', '=', $request->brand);
        }
        $products = $products->get();
        return Helper::SuccessReturn($products, 'SHOPPING_PRODUCTS_LIST');
    }
    public function shoppingProductsGroups(Request $request)
    {
        $user = $request->user();
        $products = ProductsGroup::query();
        // $products = $products->whereNot('user_id','=',$user->user_id);
        $products = $products->where('trash', '=', 0);
        $products = $products->get();
        return Helper::SuccessReturn($products, 'SHOPPING_PRODUCTS_GROUP_LIST');
    }
    public function shoppingProductsBrands(Request $request)
    {
        $user = $request->user();
        $rules = [
            'group' => ['required', Rule::exists('products_groups', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $products = ProductsBrands::query();
        // $products = $products->whereNot('user_id','=',$user->user_id);
        $products = $products->where(['trash' => 0, 'group' => $request->group]);
        $products = $products->get();
        return Helper::SuccessReturn($products, 'SHOPPING_PRODUCTS_BRAND_LIST');
    }

    // single product
    public function shoppingProduct(Request $request)
    {

        $rules = [
            'product_id' => ['required', Rule::exists('products', 'id')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = Products::where('id', $request->product_id)->first();
        return  Helper::SuccessReturn($data, 'PRODUCT_FATCHED');
    }
    // carts options
    public function cart(Request $request)
    {
        $user =  $request->user();
        $data =  Cart::where('user_id', '=', $user->user_id)->get();
        return Helper::SuccessReturn($data, 'CART_LIST');
    }
    public function cartAdd(Request $request)
    {
        $user = $request->user();
        $rules = [
            "item" => ['required', Rule::exists('products', 'id')],
            "quantity" => ['required', 'numeric'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $product = Products::where('id', $request->item)->first();
        // prduct filter condition
        Cart::updateOrCreate([
            'user_id' => $user->user_id,
            'seller_id' => $product->user_id,
            'product_id' => $product->id,
        ], [
            'user_id' => $user->user_id,
            'seller_id' => $product->user_id,
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => $product->image,
            'unit_type' => $product->unit_type,
            'price' => $product->price,
            'tax' => (($product->price * $product->tax) / 100),
            'weight' => $product->weight,
            'discount' => 0,
            'quantity' => $request->quantity,
            // 'total' => $product->price * $request->quantity,
            'total' => (($product->price * $request->quantity) + (($product->price * $request->quantity * $product->tax) / 100))
        ]);
        return Helper::SuccessReturn(null, "CART_ADD");
    }
    public function cartUpdate(Request $request)
    {
        $user = $request->user();
        $rules = [
            "cart" => ['required', Rule::exists('carts', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
            "quantity" => ['required', 'numeric'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $cart = Cart::where(['user_id' => $user->user_id, 'id' => $request->cart])->first();
        $product = Products::where(['id' => $cart->product_id, 'user_id' => $cart->seller_id])->first();
        $cart->update([
            'price' => $product->price,
            'quantity' => $request->quantity,
            'tax' => (($product->price * $product->tax) / 100),
            'total' => (($product->price * $request->quantity) + (($product->price * $request->quantity * $product->tax) / 100))
        ]);
        return Helper::SuccessReturn($cart, "CART_ADD");
    }
    public function cartRemove(Request $request)
    {
        $user = $request->user();
        $rules = [
            "cart" => ['required', Rule::exists('carts', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $cart = Cart::where(['user_id' => $user->user_id, 'id' => $request->cart])->delete();
        return Helper::SuccessReturn(null, "CART_UPDATED");
    }
    public function checkout(Request $request)
    {
        $user = $request->user();
        $rules = [
            "carts" => ['required', 'array'],
            "carts.*" => ['required', 'numeric', Rule::exists('carts', 'id')->where(function ($query) use ($user) {
                return $query->where(['user_id' => $user->user_id]);
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $carts = Cart::whereIn('id', $request->carts)->where(['user_id' => $user->user_id])->get();
        $order_id = 'ORD_' . now()->timestamp;
        $tnx = 'TNX_' . now()->timestamp;
        // $total = $carts->sum('total');
        foreach ($carts as $cart) {
            Orders::create([
                'order_id' => $order_id,
                'buyer_id' => $user->user_id,
                'seller_id' => $cart->seller_id,
                'product_id' => $cart->product_id,
                'payment_id' => $tnx,
                'quantity' => $cart->quantity,
                'payment_method' => 1,
                'price' => $cart->total
            ]);
            OrderItem::create([
                'order_id' => $order_id,
                'product_id' => $cart->product_id,
                'name' => $cart->name,
                'image' => 'storage/' . $cart->seller_id . '/products/' . $cart->image,
                'unit_type' => $cart->unit_type,
                'price' => $cart->price,
                'weight' => $cart->weight,
                'tax' => $cart->tax,
                'discount' => $cart->discount,
                'quantity' => $cart->quantity,
                "total" => $cart->total
            ]);
            $cart->delete();
        }
        return Helper::SuccessReturn(['order_id' => $order_id, 'payment_id' => $tnx], 'ORDER_PLACED');
    }
    public function orders(Request $request)
    {
        $user = $request->user();
        // return  $orders = Orders::where('orders.buyer_id', $user->user_id)
        //     ->leftjoin('order_items', 'order_items.order_id', '=', 'orders.order_id')
        //     ->select(
        //         'orders.order_id',
        //         // 'orders.product_id',
        //         'orders.payment_id',
        //         // 'orders.quantity',
        //         'orders.status',
        //         'orders.payment_method',
        //         // 'orders.price as amount',
        //         'order_items.name',
        //         'order_items.image',
        //         'order_items.unit_type',
        //         'order_items.price',
        //         'order_items.weight',
        //         // 'order_items.tax',
        //         // 'order_items.discount',
        //         'order_items.quantity',
        //         "order_items.total"
        //     )
        //     //   ->groupby('order_id')
        //     ->get();
        $orders = Orders::where('buyer_id', $user->user_id)
            ->with(['order_items' => function ($query) {
                $query->select(
                    'order_items.order_id',
                    'order_items.product_id',
                    'order_items.name',
                    'order_items.image',
                    'order_items.unit_type',
                    'order_items.price',
                    'order_items.weight',
                    'order_items.quantity',
                    'order_items.total'
                );
            }])
            ->select(
                'orders.order_id',
                'orders.product_id',
                'orders.status',
                'orders.price as amount'
            )
            ->get()->toArray();
        return Helper::SuccessReturn($orders, 'MY_ORDER_LIST');
    }
}
