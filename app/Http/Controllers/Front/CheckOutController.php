<?php

namespace App\Http\Controllers\Front;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckOutController extends Controller
{
    public function index(Request $request, $slug)
    {
        $item = Item::with(['type', 'brand'])->whereSlug($slug)->firstOrFail();

        return view('checkout', [
            'item' => $item
        ]);
    }

        public function store(Request $request, $slug)
        {
            $request ->validate([
                'name' => 'required|string|max:255',
                'start_date' => 'required',
                'end_date' => 'required',
                'address' => 'required',
                'city' => 'required',
                'zip'=>'required',
            ]);

            // Format start_date and end_date from dd mm yy to timestamp
            $start_date = Carbon::createFromFormat('d m Y', $request->start_date);
            $end_date = Carbon::createFromFormat('d m Y', $request->end_date);
            
            //Count the number of days bettween start_date and end_date
            $days = $start_date->diffInDays($end_date);

            //Get the item
            $item = Item::whereSlug($slug)->firstOrFail();

            //Calculate the total price
            $total_price = $days * $item->price;

            //add 10% tax
            $total_price = $total_price + ($total_price * 0.1);

            //Create the booking
            $booking = $item->bookings()->create([
                'name' => $request->name,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'address' => $request->address,
                'city' => $request->city,
                'zip' => $request->zip,
                'user_id' => auth::user()->id,
                'total_price' => $total_price,
            ]);

            return redirect()->route('front.payment', $booking->id);
        }
    }

