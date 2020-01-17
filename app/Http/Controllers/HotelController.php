<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HotelProviders\TopHotelsProvider;
use App\HotelProviders\BestHotelProvider;

class HotelController extends Controller
{
    /**
     * Add new providers here with a name to be used in the code.
     */
    private $providers = [
        'TopHotels' => TopHotelsProvider::class,
        'BestHotel' => BestHotelProvider::class,
    ];

    /**
     * Return all hotels from given providers.
     *
     * @param request $request
     * @return array hotels
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'date|date_format:d-m-Y',
            'to_date' => 'date|date_format:d-m-Y',
            'city' => 'string|max:3',
            'adults_number' => 'integer',
        ]);

        // Fixing format
        $request['city'] = strtoupper($request->city);

        // Pass the provider's names to the getHotels function and the request
        $apisResponse = $this->getHotels($request, ['TopHotels', 'BestHotel']);
        $hotels = collect($apisResponse)
            ->sortBy('fare')->values()->all();

        return response($hotels, 200);
    }

    /**
     * Get hotels from APIs.
     *
     * @param  request $request
     * @param  array $apis
     * @return array
     */
    public function getHotels($request, $apis)
    {
        $hotels = collect([]);

        foreach ($apis as $key => $value) {
            $provider = new $this->providers[$value];
            $res = $provider->Search($request);

            foreach ($res as $k => $v) {
                $hotels->push($v);
            }
        }

        return $hotels;
    }
}
