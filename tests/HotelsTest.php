<?php

class HotelsTest extends TestCase
{
    /**
     * Test response status.
     *
     * @return void
     */
    public function testStatus()
    {
        $response = $this->call('POST', '/');
        $this->assertEquals(200, $response->status());
    }

    /**
     * Test JSON structure.
     *
     * @return void
     */
    public function testHotelStructure()
    {
        $this->post('/', [
            "from_date" => "15-01-2020",
            "to_date" => "16-01-2020",
            "city" => "HBE",
            "adults_number" => 2
        ])
            ->seeJsonStructure([
                [
                    'provider',
                    'hotelName',
                    'fare',
                    'amenities' => [],
                ]
            ]);
    }

    /**
     * Test JSON data.
     *
     * @return void
     */
    public function testHotelsJson()
    {
        $this->json('POST', '/', [
            "from_date" => "15-01-2020",
            "to_date" => "16-01-2020",
            "city" => "HBE",
            "adults_number" => 2
        ])
            ->seeJson(
                [
                    "provider" => "BestHotel",
                    "hotelName" => "hotel2",
                    "fare" => 1439.82,
                    "amenities" => [
                        "breakfast"
                    ]
                ]
            );
    }
}
