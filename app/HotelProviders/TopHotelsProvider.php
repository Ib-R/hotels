<?php

namespace App\HotelProviders;

use GuzzleHttp\Client;

class TopHotelsProvider
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
                    'provider' => 'TopHotels',
                    'hotelName' => $hotel->hotelName ?? 'Unknown',
                    'fare' => isset($hotel->discount) ? $hotel->price - $hotel->price * $hotel->discount / 100 : $hotel->price,
                    'amenities' => $hotel->amenities ?? []
                ];
            });
        return $hotels;
    }
}
