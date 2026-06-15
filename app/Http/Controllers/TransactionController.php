<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if (! session('insecure_user_id')) {
            return redirect('/login')->with('error', 'Please log in first.');
        }

        // LAB ONLY: user_id can be changed in the URL to view another user's transactions.
        $userId = $request->input('user_id', session('insecure_user_id'));
        $transactions = DB::select("SELECT * FROM transactions WHERE user_id = $userId ORDER BY id DESC");

        return view('lab.transactions.index', [
            'transactions' => $transactions,
            'selectedUserId' => $userId,
        ]);
    }

    public function store(Request $request)
    {
        if (! session('insecure_user_id')) {
            return redirect('/login')->with('error', 'Please log in first.');
        }

        // LAB ONLY: trusts client-side user_id, price, and quantity values.
        $userId = $request->input('user_id');
        $productName = $request->input('product_name');
        $price = $request->input('price');
        $quantity = $request->input('quantity');
        $shippingAddress = $request->input('shipping_address');
        $note = $request->input('note');

        DB::statement(
            "INSERT INTO transactions (user_id, product_name, price, quantity, shipping_address, note, status, created_at, updated_at)
             VALUES ($userId, '$productName', $price, $quantity, '$shippingAddress', '$note', 'pending', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
        );

        return redirect('/transactions')->with('message', 'Transaction created.');
    }

    public function updateStatus(Request $request, int $id)
    {
        if (! session('insecure_user_id')) {
            return redirect('/login')->with('error', 'Please log in first.');
        }

        // LAB ONLY: any logged-in user can update any transaction status by id.
        $status = $request->input('status');
        DB::statement("UPDATE transactions SET status = '$status', updated_at = CURRENT_TIMESTAMP WHERE id = $id");

        return redirect('/transactions')->with('message', 'Status updated.');
    }
}
