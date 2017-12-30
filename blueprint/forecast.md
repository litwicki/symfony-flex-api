## Forecast [/forecast]

### View Forecast [GET /forecast/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Forecast in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Forecast1)
        + message: {REVENUE_NAME} retrieved successfully.

### Create new Forecast [POST /forecast]

+ Request (application/json)

        {
            "title": "Our first sale!",
            "body": "This is our first official product sale as a new business!",
            "category": 1,
            "organization": 1,
            "user": 43
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Forecast1)
        + message: {REVENUE_NAME} created successfully.

### Edit Forecast [PATCH /forecast/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Forecast in the form of an integer

+ Request (application/json)

        {
            "title": "Our first sale!",
            "body": "This is our first official product sale as a new business!",
            "category": 1,
            "organization": 1,
            "user": 43
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Forecast1)
        + message: {REVENUE_NAME} updated successfully.

### Delete Forecast [DELETE /forecast/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Forecast in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {REVENUE_NAME} removed successfully.

