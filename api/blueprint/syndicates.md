## Syndicate [/syndicate]

### View Syndicate [GET /syndicate/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Syndicate in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Syndicate1)
        + message: {REVENUE_NAME} retrieved successfully.

### Create new Syndicate [POST /syndicate]

+ Request (application/json)

        {
            "name": "Syndicate Name",
            "leader": 1,
            "investors": [1,3,7]
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Syndicate1)
        + message: {REVENUE_NAME} created successfully.

### Edit Syndicate [PATCH /syndicate/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Syndicate in the form of an integer

+ Request (application/json)

        {
            "name": "Syndicate Name",
            "leader": 1,
            "investors": [1,3]
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Syndicate1)
        + message: {REVENUE_NAME} updated successfully.

### Delete Syndicate [DELETE /syndicate/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Syndicate in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {REVENUE_NAME} removed successfully.

