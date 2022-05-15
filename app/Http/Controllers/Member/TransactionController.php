<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Package;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $package = Package::find($request->package_id);

        $transaction = Transaction::create([
            'package_id' => $package->id,
            'user_id' => auth()->user()->id,
            'amount' => $package->price,
            'transaction_code' => strtoupper(Str::random(10)),
            'status' => 'pending'
        ]);

        $customer = auth()->user();

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->transaction_code,
                'gross_amount' => $transaction->amount
            ],
            'customer_details' => [
                'first_name' => $customer->name,
                'last_name' => $customer->name,
                'email' => $customer->email
            ],
            'enabled_payments' => [
                'credit_card',
                'bca_va',
                'bni_va',
                'bri_va'
            ]
        ];

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = (bool) env('MIDTRANS_IS_SANITIZED', true);
        \Midtrans\Config::$is3ds = (bool) env('MIDTRANS_IS_3DS', true);

        $createMidtransTransaction = \Midtrans\Snap::createTransaction($params);
        $midtransRedirectUrl =  $createMidtransTransaction->redirect_url;

        return redirect($midtransRedirectUrl);
    }
}
