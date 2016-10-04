## Funding [/funding]

### Get Funding [GET /funding/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Funding in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Funding1)
        + message: {FUNDING_NAME} retrieved successfully.

### Create Funding [POST]

+ Request (application/json)

        {
            "account": 1,
            "type: "Seed",
            "total_shares": 1000000,
            "share_price": 1.00,
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Funding1)
        + message: {FUNDING_NAME} retrieved successfully.

### Edit Funding [PATCH /funding/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Funding in the form of an integer

+ Request (application/json)

        {
            "account": 1,
            "type: "Seed",
            "total_shares": 1000000,
            "share_price": 2.50,
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Funding1)
        + message: {FUNDING_NAME} retrieved successfully.

### Delete Funding [DELETE /funding/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Funding in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {FUNDING_NAME} removed successfully.