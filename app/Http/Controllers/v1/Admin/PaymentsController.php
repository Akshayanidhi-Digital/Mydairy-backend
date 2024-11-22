<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackagePurchaseHistroy;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index()
    {
        $title = 'Plan Purchase Transaction History';
        $datas = PackagePurchaseHistroy::with('plan', 'user')->orderby('payment_status')->paginate(env('PER_PAGE_RECORDS'));
        return view('admin.payments.index', compact('title', 'datas'));
    }
    // public function print($pay_id)
    // {
    //     return $data = PackagePurchaseHistroy::with('plan', 'user')->where('payment_id', $pay_id)->first();
    // }

    public function print($pay_id)
    {
        $data = PackagePurchaseHistroy::with('plan', 'user')->where('payment_id', $pay_id)->first();

        // Check if the data exists
        if (!$data) {
            return redirect()->route('admin.payments.index')->with('error', 'Payment not found');
        }

        // Return the data to a 'print' view
        return view('admin.payments.print', compact('data'));
    }
}
