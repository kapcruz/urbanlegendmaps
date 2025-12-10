URL: http://localhost:8000/api

# Urban Legends API

It uses token-based authentication and returns JSON responses.

All endpoints require a `Bearer` token in the `Authorization` header.

+ Header
    + Authorization: Bearer `{API_SECRET_KEY}`
    + Accept: application/json
    + Content-Type: application/json

---

# Data Structures

## UrbanLegend (object)
+ uuid: `91149e31-f11e-4ad2-a86b-123456789abc` (string, required) - Public identifier (UUID v4)
+ title: `Loira do Banheiro` (string, required) - Legend title
+ slug: `loira-do-banheiro` (string, required) - Unique slug generated from the title
+ description: `Loira que assustava geral...` (string, optional)
+ latitude: -22.82 (number, required)
+ longitude: -45.19 (number, required)
+ country: `BR` (string, required) - ISO country code (must exist in `config('countries')`)
+ city: `Brasília` (string, required)

## ValidationError (object)
+ message: `Validation error.` (string)
+ errors (object) - Field-specific error messages

    + title (array[string], optional)
    + latitude (array[string], optional)
    + longitude (array[string], optional)
    + country (array[string], optional)
    + city (array[string], optional)

---

# Urban Legends

## List Legends [/legend{?country,city,uuid,slug}]

List of urban legends.  
You can optionally filter by country, city, uuid, or slug.

+ Parameters
    + country: `BR` (string, optional) - Country code filter
    + city: `Brasília` (string, optional) - City name filter
    + uuid: `91149e31-f11e-4ad2-a86b-123456789abc` (string, optional) - Filter by specific legend UUID
    + slug: `loira-do-banheiro` (string, optional) - Filter by slug

### List Legends [GET]

+ Request List Legends (application/json)

    + Headers

            Authorization: Bearer {API_SECRET_KEY}
            Accept: application/json

+ Response 200 (application/json)

    + Attributes
        + data (array[UrbanLegend])

    + Body

            {
              "data": [
                {
                  "uuid": "91149e31-f11e-4ad2-a86b-123456789abc",
                  "title": "Loira do Banheiro",
                  "slug": "loira-do-banheiro",
                  "description": "Loira que assustava geral...",
                  "latitude": -22.82,
                  "longitude": -45.19,
                  "country": "BR",
                  "city": "Brasília"
                }
              ]
            }

---

## Create Legend [/legend]

Create a new urban legend.  
The associated user is resolved internally (e.g., `User::first()`), and any `slug` sent by the client is ignored.  
A unique slug is generated based on the title.

### Create Legend [POST]

+ Request Create Legend (application/json)

    + Headers

            Authorization: Bearer {API_SECRET_KEY}
            Content-Type: application/json
            Accept: application/json

    + Attributes (object)
        + title: `Loira do Banheiro` (string, required)
        + description: `Loira que assustava geral...` (string, optional)
        + latitude: -22.82 (number, required)
        + longitude: -45.19 (number, required)
        + country: `BR` (string, required)
        + city: `Brasília` (string, required)

    + Body

            {
              "title": "Loira do Banheiro",
              "description": "Loira que assustava geral...",
              "latitude": -22.82,
              "longitude": -45.19,
              "country": "BR",
              "city": "Brasília"
            }

+ Response 201 (application/json)

    + Attributes
        + data (UrbanLegend)

    + Body

            {
              "data": {
                "uuid": "c631880c-09ad-4192-a9af-db16d9c8b443",
                "title": "Loira do Banheiro",
                "slug": "loira-do-banheiro",
                "description": "Loira que assustava geral...",
                "latitude": -22.82,
                "longitude": -45.19,
                "country": "BR",
                "city": "Brasília"
              }
            }

+ Response 422 (application/json)

    + Attributes (ValidationError)

    + Body

            {
              "message": "Validation error.",
              "errors": {
                "title": [
                  "Set a title. Title is required."
                ],
                "latitude": [
                  "Set a latitude. Latitude is required."
                ],
                "longitude": [
                  "Set a longitude. Longitude is required."
                ],
                "country": [
                  "Set a country. Country is required."
                ],
                "city": [
                  "Set a city. City is required."
                ]
              }
            }

+ Response 422 (application/json)

    + Description

        Returned when no user exists in the system.

    + Body

            {
              "message": "Validation error.",
              "errors": {
                "user_id": [
                  "Need create a user before creating an urban legend."
                ]
              }
            }

---

## Legend Details [/legend/{uuid}]

+ Parameters
    + uuid: `91149e31-f11e-4ad2-a86b-123456789abc` (string, required) - Legend UUID

### Update Legend [PUT]

Update fields of an existing legend.  
If the title changes, the model regenerates `title_key` and a unique `slug`.

+ Request Update Legend (application/json)

    + Headers

            Authorization: Bearer {API_SECRET_KEY}
            Content-Type: application/json
            Accept: application/json

    + Attributes (object)
        + title: `Loira da Escada` (string, optional)
        + description: `Updated description...` (string, optional)
        + latitude: -22.80 (number, optional)
        + longitude: -45.20 (number, optional)
        + country: `BR` (string, optional)
        + city: `Brasília` (string, optional)

    + Body

            {
              "title": "Loira da Escada"
            }

+ Response 200 (application/json)

    + Attributes
        + data (UrbanLegend)

    + Body

            {
              "data": {
                "uuid": "91149e31-f11e-4ad2-a86b-123456789abc",
                "title": "Loira da Escada",
                "slug": "loira-da-escada",
                "description": "Loira que assustava geral...",
                "latitude": -22.82,
                "longitude": -45.19,
                "country": "BR",
                "city": "Brasília"
              }
            }

+ Response 404 (application/json)

    + Body

            {
              "message": "Not Found"
            }

+ Response 422 (application/json)

    + Attributes (ValidationError)

---

### Delete Legend [DELETE]

Soft-deletes a legend identified by `uuid`.

+ Request Delete Legend

    + Headers

            Authorization: Bearer {API_SECRET_KEY}
            Accept: application/json

+ Response 204

    + Description

        Legend successfully soft-deleted. No content is returned.

+ Response 404 (application/json)

    + Body

            {
              "message": "Not Found"
            }

---

# Rules

+ Titles **can be duplicated** (including with soft-deleted records).
+ Slugs are **always unique**, even when there are multiple legends with the same title.
+ Any `slug` sent by the client is ignored; slugs are generated automatically in the model using Eloquent events.
