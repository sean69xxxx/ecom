<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        // Use Laravel's built-in Auth check
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please log in first.');
        }

        // 1. Fix IDOR & Authorization: Strictly use the authenticated user's ID
        $userId = Auth::id();

        // 2. Prevent SQL Injection: Use Query Builder
        $transactions = DB::table('transactions')
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get();

        return view('lab.transactions.index', [
            'transactions' => $transactions,
            'selectedUserId' => $userId,
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please log in first.');
        }

        // 1. Input Validation: Notice we COMPLETELY REMOVED the 'price' validation.
        // We do not care what price the client sends, so we don't even check it.
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1|max:100',
            'shipping_address' => 'required|string|max:500',
            'note' => 'nullable|string|max:500',
        ]);

        // 2. SERVER-SIDE PRICE LOOKUP (The Real Fix)
        // In a real app, you would fetch this from a Products database table.
        $officialPrices = [
            'Keyboard' => 19.90,
            'Wireless Mouse' => 29.90,
            'USB-C Charger' => 49.90,
        ];

        // Ensure the product exists in our system to prevent manipulation
        if (!array_key_exists($validated['product_name'], $officialPrices)) {
            return back()->with('error', 'Invalid product selected.');
        }

        // Securely fetch the official price from the server, NOT the request
        $securePrice = $officialPrices[$validated['product_name']];

        // 3. Insert using the secure server-side price
        DB::table('transactions')->insert([
            'user_id' => Auth::id(), 
            'product_name' => strip_tags($validated['product_name']),
            'price' => $securePrice, // <-- Safe!
            'quantity' => $validated['quantity'],
            'shipping_address' => strip_tags($validated['shipping_address']),
            'note' => strip_tags($validated['note']),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/transactions')->with('message', 'Transaction created securely.');
    }

    public function updateStatus(Request $request, int $id)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please log in first.');
        }

        // 1. Input Validation: Restrict allowed status values 
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
        ]);

        // 2. Access Control: Ensure the transaction actually belongs to the logged-in user [cite: 70]
        $transaction = DB::table('transactions')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$transaction) {
            // 3. Proper Error Handling: Generic failure 
            return redirect('/transactions')->with('error', 'Transaction not found or unauthorized.');
        }

        // 4. Parameterized Update
        DB::table('transactions')
            ->where('id', $id)
            ->update([
                'status' => $validated['status'],
                'updated_at' => now(),
            ]);

        return redirect('/transactions')->with('message', 'Status updated successfully.');
    }
}