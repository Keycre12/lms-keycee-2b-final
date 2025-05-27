<?php

namespace App\Http\Controllers;

use App\Models\BookTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookTransactionController extends Controller
{
    public function getTransactions()
    {
        $transactions = BookTransaction::with('book', 'user')->get();
        return response()->json(['transactions' => $transactions]);
    }

    public function addTransaction(Request $request)
    {
        $transactions = $request->all();

        if (isset($transactions[0])) {
            // Batch creation
            $created = [];

            foreach ($transactions as $transactionData) {
                $validator = Validator::make($transactionData, [
                    'book_id' => 'required|exists:books,id',
                    'user_id' => 'required|exists:users,id',
                    'tr_type' => 'required|in:restock,damaged,borrowed',
                    'quantity' => 'required|integer|min:1',
                    'date' => 'required|date',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                $created[] = BookTransaction::create($transactionData);
            }

            return response()->json(['message' => 'Transactions created successfully!', 'transactions' => $created]);
        } else {
            // Single transaction creation
            $request->validate([
                'book_id' => 'required|exists:books,id',
                'user_id' => 'required|exists:users,id',
                'tr_type' => 'required|in:restock,damaged,borrowed',
                'quantity' => 'required|integer|min:1',
                'date' => 'required|date',
            ]);

            $transaction = BookTransaction::create($request->all());

            return response()->json(['message' => 'Transaction created successfully!', 'transaction' => $transaction]);
        }
    }

    public function editTransaction(Request $request, $id)
    {
        $transaction = BookTransaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'tr_type' => 'required|in:restock,damaged,borrowed',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        $transaction->update($request->all());

        return response()->json(['message' => 'Transaction updated successfully!', 'transaction' => $transaction]);
    }

    public function deleteTransaction($id)
    {
        $transaction = BookTransaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully!']);
    }
}
