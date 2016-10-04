## Contact [/contacts]

### Get All Contacts [GET]

+ Response 200 (application/json)

    + Attributes
        + data (array) - Response data
            + (Contact1)
            + (Contact2)
        + message: {CONTACT_NAME} retrieved successfully; last updated {UPDATE_DATE}.

### Get Contact [GET /contacts/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Contact in the form of an integer

+ Response 200 (application/json)

    + Attributes
        + data (array) - Response data
            + (Contact1)
        + message: {CONTACT_NAME} retrieved successfully; last updated {UPDATE_DATE}.

### Create new Contact [POST]

+ Request (application/json)

        {
            "first_name": "Jane",
            "last_name": "Doe",
            "title": "CEO",
            "address": "123 Main St",
            "address2": "",
            "city": "Maple Valley",
            "state": "WA",
            "zip": "98065",
            "email": "jane.doe@gmail.com",
            "phone": "555-867-5309",
            "user": 1,
            "account": 1
        }

+ Response 200 (application/json)

    + Attributes
        + data (array) - Response data
            + (Contact1)
        + message: {CONTACT_NAME} created successfully.

### Edit Contact [PATCH /contacts/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Contact in the form of an integer

+ Request (application/json)

        {
            "status": 1
            "first_name": "Jane",
            "last_name": "Doe",
            "title": "CEO",
            "address": "123 Main St",
            "address2": "",
            "city": "Maple Valley",
            "state": "WA",
            "zip": "98065",
            "email": "jane.doe@gmail.com",
            "phone": "555-867-5309",
            "user": 1,
            "account": 1
        }

+ Response 200 (application/json)

    + Attributes
        + data (array) - Response data
            + (Contact1)
        + message: {CONTACT_NAME} retrieved successfully; last updated {UPDATE_DATE}.

### Delete Contact [DELETE /contacts/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Contact in the form of an integer

+ Attributes
    + data (array) - Response data
        + (Contact1)
    + message: {CONTACT_NAME} removed successfully.