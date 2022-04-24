<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $relations = [
            'package',
            'user'
        ];

        $transactions = Transaction::with($relations)->get();

        return view('admin.transactions', ['transactions' => $transactions]);
    }
}
