## Shareholder [/shareholders]

### View Shareholder [GET /shareholders/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Shareholder in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Shareholder1)
        + message: {SHAREHOLDER_NAME} retrieved successfully.

### Create new Shareholder [POST /shareholders]

+ Request (application/json)

        {
            "first_name": "Jane",
            "last_name": "Doe",
            "title": "Semi Retired",
            "address": "123 Main St",
            "city": "Seattle",
            "state": "WA",
            "zip": 98065,
            "email": "jane.doe@company.com",
            "phone": "555-222-9595",
            "notes": "",
            "shares": 100,
            "funding_round": 2
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Shareholder1)
        + message: {SHAREHOLDER_NAME} retrieved successfully.

### Edit Shareholder [PATCH /shareholders/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Shareholder in the form of an integer

+ Request (application/json)

        {
            "first_name": "Jane",
            "last_name": "Doe",
            "title": "Venture Capitalist",
            "address": "1212 Main St",
            "city": "Seattle",
            "state": "WA",
            "zip": 98101,
            "email": "jane.doe@company.com",
            "phone": "555-222-9595",
            "notes": "Paid by check in person on Tuesday",
            "shares": 1000
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Shareholder1)
        + message: {SHAREHOLDER_NAME} updated successfully.

### Delete Shareholder [DELETE /shareholders/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Shareholder in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {SHAREHOLDER_NAME} removed successfully.