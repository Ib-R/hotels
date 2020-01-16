<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class HotelController extends Controller
{
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
            $res = $this->callApi($request, $value);
            foreach ($res as $k => $v) {
                $hotels->push($v);
            }
        }

        return $hotels;
    }

    /**
     * Get hotels from API.
     *
     * @param  request $request
     * @param  string $api
     * @return array
     */
    public function callApi($request, $api)
    {
        $client = new Client(['http_errors' => false]);

        // Add new case for each new provider's API
        switch ($api) {
            case 'TopHotels': // Provider name
                $res = $client->post(
                    "http://ourhotels.pro/tophotels", // Provider API URL
                    [
                        'json' => [
                            'from' =>  date('c', strtotime($request->from_date)),
                            'to' => date('c', strtotime($request->to_date)),
                            'city' => $request->city,
                            'adultsCount' => $request->adults_number
                        ]
                    ]
                );

                // Handling response
                if ($res->getStatusCode() == 200) {
                    $hotels = collect(json_decode($res->getBody()))
                        ->map(function ($hotel) {
                            return [
                                'provider' => 'TopHotels',
                                'hotelName' => $hotel->hotelName ?? 'Unknown',
                                'fare' => isset($hotel->discount) ? $hotel->price - $hotel->price * $hotel->discount / 100 : $hotel->price,
                                'amenities' => $hotel->amenities ?? []
                            ];
                        });
                    return $hotels;
                } else {
                    return collect([]);
                }

            case 'BestHotel':
                $res = $client->post(
                    "http://ourhotels.pro/besthotel",
                    [
                        'json' => [
                            'fromDate' =>   $request->from_date,
                            'toDate' => $request->to_date,
                            'city' => $request->city,
                            'numberOfAdults' => $request->adults_number
                        ]
                    ]
                );
                if ($res->getStatusCode() == 200) {
                    $hotels = collect(json_decode($res->getBody()))
                        ->map(function ($hotel) {
                            return [
                                'provider' => 'BestHotel',
                                'hotelName' => $hotel->hotel ?? 'Unknown',
                                'fare' => round($hotel->hotelFare, 2),
                                'amenities' => isset($hotel->roomAmenities) ? explode(',', $hotel->roomAmenities) : []
                            ];
                        });
                    return $hotels;
                } else {
                    return collect([]);
                }

            default:
                return collect([]);
        }
    }
}
