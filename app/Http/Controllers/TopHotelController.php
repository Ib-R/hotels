<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopHotelController extends Controller
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
                'hotelName' => 'hotel1',
                'rate' => '*****',
                'price' => 1200 * $request->adultsCount,
                'discount' => 10,
                'amenities' => ['breakfast', 'sea view']
            ],
            [
                'city' => 'HBE',
                'hotelName' => 'hotel2',
                'rate' => '****',
                'price' => 1000 * $request->adultsCount,
                'discount' => 5,
                'amenities' => ['breakfast']
            ]
        ]);
        $hotels = $request->city ? $hotels->where('city', $request->city) : $hotels;

        return response(json_encode($hotels), 200);
    }
}
