<?php

namespace App\HotelProviders;

use GuzzleHttp\Client;

class BEstHotelProvider
{
    /**
     * Search for hotels.
     *
     * @param array $request
     * @return array hotels
     */
    public function Search($request)
    {
        $client = new Client(['http_errors' => false]);

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
            return $this->Transform($res->getBody());
        }

        return collect([]);
    }

    /**
     * Transform results to the correct format.
     *
     * @param array $result
     * @return array hotels
     */
    public function Transform($result)
    {
        $hotels = collect(json_decode($result))
            ->map(function ($hotel) {
                return [
                    'provider' => 'BestHotel',
                    'hotelName' => $hotel->hotel ?? 'Unknown',
                    'fare' => round($hotel->hotelFare, 2),
                    'amenities' => isset($hotel->roomAmenities) ? explode(',', $hotel->roomAmenities) : []
                ];
            });
        return $hotels;
    }
}
