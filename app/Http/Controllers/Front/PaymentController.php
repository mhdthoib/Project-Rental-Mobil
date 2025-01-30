<?php

namespace App\Http\Controllers\Front;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    function index(Request $request, $bookingId ){

        $booking = Booking::with(['item.brand', 'item.type'])->findOrFail($bookingId);
        return view('payment',[
        'booking' => $booking
            ]);
    }

    function detail(Request $request, $bookingId ){

        $booking = Booking::with(['item.brand', 'item.type'])->findOrFail($bookingId);

        return view('payment-detail',[
            'booking' => $booking
        ]);
    }

    function update(Request $request, $bookingId ){

        $booking = Booking::findOrFail($bookingId);
        $booking->payment_method = $request->payment_method;

        if($request->payment_method == 'midtrans'){
        //call midtrans API
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');

        //Get USD to IDR rate using guzzle
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.exchangerate-api.com/v4/latest/USD');
           $body = $response->getBody();
           $rate = json_decode($body)->rate->IDR;

        //Convert to IDR
        $totalPrice = $booking->total_price * $rate;

        //Create Midtrans Params
        $midtransParams =[
            'transaction_details' => [
                'order_id' => $booking->id,
                'gross_amount' => (int) $totalPrice,
            ],
            'customser_details' => [
                'first_name' => $booking->$request->name,
                'email' => $booking->$request->email,
            ],
            'enabled_payments' => [
                'gopay', 'bank_transfer'
            ],
            'vtweb' => []
        ];

        //Get Snap Payment Page URL
        $paymenUrl = \Midtrans\Snap::createTransaction($midtransParams)->redirect_url;

        // Save payment URL to Booking
        $booking->payment_url = $paymenUrl;
        $booking->save();

        //Redirect to Snap Payment Page
        return redirect($paymenUrl);
        }
        return redirect()->route('front.index');
        }
        public function success(Request $request, $bookingId )
        {
        return view('success');
        }
}
