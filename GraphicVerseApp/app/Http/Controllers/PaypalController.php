<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public function payment(Request $request)
    {

        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to register route if user is not authenticated
        }
   
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                // "return_url" => route('paypal_success'),

                "return_url" => route('paypal_success', [
                   'package_id' => $request->package_id,
                   'artwork_id' => $request->artwork_id,
                    'price' => $request->price
                ]),

                "cancel_url" => route('paypal_cancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $request->price
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {

            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('paypal_cancel');
        }
    }

    public function success(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);
        if (isset($response['status']) && $response['status'] === "COMPLETED") {

            // Extract purchase details from the PayPal response
            $packageID = $request->query('package_id');
            $artworkID = $request->query('artwork_id');
            $price = $request->query('price');
            // Get the package ID from the request
            // Assuming the user is authenticated, retrieve the user's ID
            $userID = Auth::id();
            // Create a new purchase record
            $purchase = new Purchase;
            $purchase->UserID = $userID;
            $purchase->package_id = $packageID;
            $purchase->artwork_id = $artworkID;
            $purchase->price = $price;
            $purchase->save();

            return view('paypal.success');
        } else {
            return redirect()->route('paypal_cancel');
        }
    }

    public function cancel()
    {
        return view('paypal.cancel');
    }


}
