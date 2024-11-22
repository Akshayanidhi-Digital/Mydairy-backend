<?php

namespace App\Http\Controllers\v1;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ShoppingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $title = __('constants.Shopping');
        $user_id = ($user->is_subdairy()) ?  $user->parent_id : 'Admin_001';
        $products = Products::where(['user_id' => $user_id])->paginate(12);
        return view('user.shopping.index', compact('title', 'products'));
    }
    public function cart()
    {
        $user = auth()->user();
        $title = __('lang.Shopping Cart');
        $carts = Cart::where('user_id', $user->user_id)->get();
        $carts->map(function ($cart) {
            $cart->image_url = asset('storage/' . $cart->seller_id . '/products/' . $cart->image);
            return $cart;
        });
        return view('user.shopping.cart.index', compact('title', 'carts'));
    }
    public function addTocart(Request $request)
    {
        $user = auth()->user();
        $rules = [
            "product" => ['required', Rule::exists('products', 'id')],
            "quantity" => ['nullable', 'numeric'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $product = Products::where('id', $request->product)->first();
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
            'quantity' => $request->input('quantity', 1),
            // 'total' => $product->price * $request->quantity,
            'total' => (($product->price *  $request->input('quantity', 1)) + (($product->price *  $request->input('quantity', 1) * $product->tax) / 100))
        ]);
        return Helper::SuccessReturn(null, "CART_ADD");
    }



    public function removeCart(Request $request)
    {
        $user = auth()->user();
        $rules = [
            "id" => ['required', Rule::exists('carts', 'id')->where(function ($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $cart = Cart::where(['id' => $request->id, 'user_id' => $user->user_id])->delete();
        return Helper::SuccessReturn(null, 'Item removed from cart');
    }



    public function updateCart(Request $request)
    {
        $user = auth()->user();
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
        return Helper::SuccessReturn(null, "CART_UPDATED");
    }
    public function checkout()
    {
        $user = auth()->user();
        $carts = Cart::where('user_id', $user->user_id)->get();
        if ($carts->count() == 0) {
            return redirect()->route('user.shopping.list');
        }
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
        return redirect()->route('user.shopping.order')->with('success', __('message.ORDER_PLACED'));
    }
    public function order(Request $request)
    {
        $user = auth()->user();
        $datas = Orders::where('buyer_id', $user->user_id)
            ->with(['order_items' => function ($query) {
                $query->select(
                    'order_items.order_id',
                    'order_items.product_id',
                    'order_items.name',
                    'order_items.image',
                    'order_items.unit_type',
                    'order_items.price',
                    'order_items.weight',
                    'order_items.tax',
                    'order_items.discount',
                    'order_items.quantity',
                    'order_items.total'
                );
            }])
            ->has('order_items')
            ->select(
                'orders.id',
                'orders.order_id',
                'orders.product_id',
                'orders.status',
                'orders.created_at',
                'orders.price as amount'
            )
            // ->getEncryptedIdAttribute()
            ->paginate(env('PER_PAGE_RECORDS'));
        $datas->getCollection()->transform(function ($item) {
            $encryptedId = Crypt::encryptString($item->id);
            \Log::info('Encrypted ID: ' . $encryptedId); // Log the encrypted ID for inspection
            $item->id = $encryptedId;
            $item->_id = $encryptedId;
            return $item;
        });
        $title = __('lang.:name List', ['name' => __('constants.Orders')]);

        return view('user.shopping.order.index', compact('title', 'datas'));
    }
    public function orderView($order_id)
    {

        $order = Orders::where(['id' => Crypt::decryptString($order_id)])
            ->with(['order_items' => function ($query) {
                $query->select(
                    'order_items.order_id',
                    'order_items.product_id',
                    'order_items.name',
                    'order_items.image',
                    'order_items.unit_type',
                    'order_items.price',
                    'order_items.weight',
                    'order_items.tax',
                    'order_items.discount',
                    'order_items.quantity',
                    'order_items.total'
                );
            }])
            ->select(
                'orders.id',
                'orders.order_id',
                'orders.product_id',
                'orders.status',
                'orders.price as amount'
            )
            ->first();
        if (!$order) {
            return  redirect()->back()->with('error', 'ORDER_NOT_FOUND');
        }
        $others = Orders::where('order_id', $order->order_id)->whereNot('id', $order->id)
            ->with(['order_items' => function ($query) {
                $query->select(
                    'order_items.order_id',
                    'order_items.product_id',
                    'order_items.name',
                    'order_items.image',
                    'order_items.unit_type',
                    'order_items.price',
                    'order_items.weight',
                    'order_items.tax',
                    'order_items.discount',
                    'order_items.quantity',
                    'order_items.total'
                );
            }])
            ->select(
                'orders.id',
                'orders.order_id',
                'orders.product_id',
                'orders.status',
                'orders.price as amount'
            )->get();
        $encryptedId = Crypt::encryptString($order->id);
        $order->id = $encryptedId;
        $order->_id = $encryptedId;
        $others->transform(function ($item) {
            $encryptedId = Crypt::encryptString($item->id);
            \Log::info('Encrypted ID: ' . $encryptedId); // Log the encrypted ID for inspection
            $item->id = $encryptedId;
            $item->_id = $encryptedId;
            return $item;
        });
        $title = 'Order view';
        return view('user.shopping.order.view', compact('title', 'order', 'others'));
    }

    public function orderPrint($order_id)
    {
        $order = Orders::where(['id' => Crypt::decryptString($order_id)])
            ->with(['order_items' => function ($query) {
                $query->select(
                    'order_items.order_id',
                    'order_items.product_id',
                    'order_items.name',
                    'order_items.image',
                    'order_items.unit_type',
                    'order_items.price',
                    'order_items.weight',
                    'order_items.tax',
                    'order_items.discount',
                    'order_items.quantity',
                    'order_items.total'
                );
            }])
            ->select(
                'orders.id',
                'orders.order_id',
                'orders.product_id',
                'orders.status',
                'orders.price as amount'
            )
            ->first();
        if (!$order) {
            return  redirect()->back()->with('error', 'ORDER_NOT_FOUND');
        }
        $others = Orders::where('order_id', $order->order_id)->whereNot('id', $order->id)
            ->with(['order_items' => function ($query) {
                $query->select(
                    'order_items.order_id',
                    'order_items.product_id',
                    'order_items.name',
                    'order_items.image',
                    'order_items.unit_type',
                    'order_items.price',
                    'order_items.weight',
                    'order_items.tax',
                    'order_items.discount',
                    'order_items.quantity',
                    'order_items.total'
                );
            }])
            ->select(
                'orders.id',
                'orders.order_id',
                'orders.product_id',
                'orders.status',
                'orders.price as amount'
            )->get();
        $encryptedId = Crypt::encryptString($order->id);
        $order->id = $encryptedId;
        $order->_id = $encryptedId;
        $others->transform(function ($item) {
            $encryptedId = Crypt::encryptString($item->id);
            \Log::info('Encrypted ID: ' . $encryptedId); // Log the encrypted ID for inspection
            $item->id = $encryptedId;
            $item->_id = $encryptedId;
            return $item;
        });
    }
}
