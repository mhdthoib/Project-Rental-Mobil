<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Booking;

class MidtransCallbackController extends Controller
{
    public function callback()
    {
        //set kofngurasi midtrans

        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //buat instance midtrans notification
        $notification = new Notification();

        //assign ke variable untuk memudahkan coding
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;

        //cari transaksi berdasarkan ID
        $booking = Booking::findOrFail($order_id);

        //handle notification status midtrans
        if ($status == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $booking->booking_status = 'PENDING';
                } else {
                    $booking->booking_status = 'SUCCESS';
                }

    }

    } elseif ($status == 'settlement') {
        $booking->booking_status = 'SUCCESS';
    }elseif ($status == 'pending') {
        $booking->booking_status = 'PENDING';
    }elseif ($status == 'deny') {
        $booking->booking_status = 'CANCELLED';
    }elseif ($status == 'expire') {
        $booking->booking_status = 'CANCELLED';
    }elseif ($status == 'cancel') {
        $booking->booking_status = 'CANCELLED';
    }

    //simpan booking
    $booking->save();

    //return response untuk midtrans
    return response()->json([
        'meta' => [
            'code' => 200,
            'message' => 'Midtrans Notification Success!'
        ]
    ]);
 }
}