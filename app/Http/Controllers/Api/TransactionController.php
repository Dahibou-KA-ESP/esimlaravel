<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function paymentCallback(Request $request)
    {
        $transactionId = $request->transaction_id;
        $status = $request->status;  // Le statut du paiement, par exemple : 'success', 'failed'

        // Récupérer la transaction correspondante
        $transaction = Transaction::where('transaction_id', $transactionId)->first();

        if ($transaction) {
            $transaction->status = $status;
            $transaction->save();

            return response()->json(['message' => 'Transaction mise à jour.']);
        }

        return response()->json(['error' => 'Transaction introuvable.'], 404);
    }

}
