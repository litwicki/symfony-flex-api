## Revenue [/revenue]

### View Revenue [GET /revenue/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Revenue in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Revenue1)
        + message: {REVENUE_NAME} retrieved successfully.

### Create new Revenue [POST /revenue]

+ Request (application/json)

        {
            "title": "Our first sale!",
            "body": "This is our first official product sale as a new business!",
            "category": 1,
            "organization": 1,
            "organization": 13,
            "user": 43
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Revenue1)
        + message: {REVENUE_NAME} created successfully.

### Edit Revenue [PATCH /revenue/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Revenue in the form of an integer

+ Request (application/json)

        {
            "title": "Our first sale!",
            "body": "This is our first official product sale as a new business!",
            "category": 1,
            "organization": 1,
            "organization": 13,
            "user": 43
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Revenue1)
        + message: {REVENUE_NAME} updated successfully.

### Delete Revenue [DELETE /revenue/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Revenue in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {REVENUE_NAME} removed successfully.

