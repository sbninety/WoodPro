<?php

namespace App\Http\Controllers;

use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller
{
    use APIResponseTrait;

    public function init(Request $request): JsonResponse
    {
        try {
            Stripe::setApiKey(env('STRIPE_SK_KEY'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount,
                'currency' => 'vnd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return $this->successResponse([
                'client_secret' => $paymentIntent->client_secret
            ], 'successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }
}
