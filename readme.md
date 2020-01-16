# OurHotels Micro-Service
OurHotels is hotel search solution that look into many providers and returns all the available hotels.
___
### Main Points
* [Technologies Used](#technologies-used)
* [API Specifications](#api-specifications)
* [Adding Providers](#adding-providers)
* [Testing](#testing)
___
## Technologies Used
* Lumen 6 (for making small micro-services)
* PHPUnit 8 (for testing)
* Guzzle 6 (HTTP client)
___

## API Specifications
### POST Get all hotels
```bash
{URL}
```
Get all hotels from API
___

#### HEADERS
Content-Type: application/json
___

#### Body
```json
{
	"from_date": ISO_LOCAL_DATE,
	"to_date": ISO_LOCAL_DATE,
	"city": "IATA Code",
	"adults_number": Integer
}
```
___
#### Response
```json
[
    {
        "provider": "STRING",
        "hotelName": "STRING",
        "fare": Integer,
        "amenities": Array
    }
]
```
___
## Adding Providers
> Adding providers is as easy as adding new case to a switch statment, and passing the name of the provider to the main function (getHotels)
___

## Testing
> The code is unit tested for response status, structure and data (demo)