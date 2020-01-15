<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BestHotelController extends Controller
{
    /**
     * Return all hotels.
     *
     * @param Request
     * @return JSON hotels
     */
    public function index(Request $request)
    {
        $hotels = collect([
            [
                'city' => 'CAI',
                'hotel' => 'hotel1',
                'hotelRate' => 5,
                'hotelFare' => 998.989 * $request->numberOfAdults,
                'roomAmenities' => 'breakfas,sea view'
            ],
            [
                'city' => 'HBE',
                'hotel' => 'hotel2',
                'hotelRate' => 4,
                'hotelFare' => 719.909 * $request->numberOfAdults,
                'roomAmenities' => 'breakfast'
            ]
        ]);
        $hotels = $request->city ? $hotels->where('city', $request->city) : $hotels;

        return response(json_encode($hotels), 200);
    }
}
