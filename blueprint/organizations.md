## Organization [/organizations]

### Get Organization [GET /organizations/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Organization in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Organization1)
        + message: {ORG_NAME} retrieved successfully.

### Create Organization [POST]

+ Request (application/json)

        {
            "account": 1,
            "name": "A New Organization",
            "description": "Our first NY City Client!",
            "website": "http://example.org",
            "address": "123 Main Street",
            "city": "New York",
            "state": "New York",
            "zip": 10001,
            "phone": "(201) 555-1234"
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Organization1)
        + message: {ORG_NAME} created successfully.

### Edit Organization [PATCH /organizations/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Organization in the form of an integer

+ Request (application/json)

        {
            "account": 1,
            "status": 1,
            "name": "A New Organization",
            "description": "Our first NY City Client!",
            "website": "http://example.org",
            "address": "123 Main Street",
            "city": "New York",
            "state": "New York",
            "zip": 10001,
            "phone": "(201) 555-1234"
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Organization1)
        + message: {ORG_NAME} updated successfully.


### Delete Organization [DELETE /organizations/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Organization in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {ORG_NAME} removed successfully.
           
### Create new Comment [POST /organizations/{id}/comments]

+ Parameters
    + id: `1` (number, required) - ID of the Organization in the form of an integer

+ Request (application/json)

        {
            "user": 1,
            "body": "Super excited to have this new client!"
        }
        
+ Response 200 (application/json)
    + Attributes
        + message: {ORG_NAME} removed successfully.