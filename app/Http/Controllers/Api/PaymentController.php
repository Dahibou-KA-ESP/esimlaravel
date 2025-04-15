<?php
namespace App\Http\Controllers;

use App\Http\Services\MobileMoneyService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'phone_number' => 'required|string',
            'provider_name' => 'required|string',
            'transaction_id' => 'required|string|unique:transactions,transaction_id',
        ]);

        try {
            $paymentService = new MobileMoneyService($request->provider_name);

            // Initiation du paiement
            $paymentResponse = $paymentService->initiatePayment(
                $request->amount,
                $request->phone_number,
                $request->transaction_id
            );

            // Si le paiement a réussi, enregistrez la transaction dans la base de données
            // (ou effectuez des actions supplémentaires ici)
            return response()->json([
                'message' => 'Paiement initié avec succès.',
                'payment_details' => $paymentResponse
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
