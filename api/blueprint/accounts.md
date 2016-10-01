## Account [/accounts]

### GET [GET /accounts/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Account1)
        + message: {ACCOUNT_NAME} retrieved successfully.

### Create new Account [POST /accounts]

+ Request (application/json)
    + Headers
        Authorization: Bearer {JSON_WEB_TOKEN}
        
    + Body
        {
            "body": "Account Name",
            "user": 1
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Account1)
        + message: {ACCOUNT_NAME} created successfully.

### Edit [PATCH /accounts/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Request (application/json)

        {
            "body": "Account Name",
            "status": 1,
            "user": 1
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Account1)
        + message: {ACCOUNT_NAME} updated successfully.

### Delete [DELETE /accounts/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer
    
+ Response 200 (application/json)
    + Attributes
        + message: {ACCOUNT_NAME} removed successfully.

### Add User [POST /accounts/{id}/users]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Request (application/json)

        {
            "body": "Account Name",
            "user": 1,
            "status": 1
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Account1)
        + message: {USER_NAME} added successfully.

### Get Users [GET /accounts/{id}/users]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)

    + Attributes (array[User1, User2])

### Remove User [DELETE /accounts/{account_id}/users/{user_id}]

+ Parameters
    + account_id: `1` (number, required) - ID of the Account in the form of an integer
    + user_id: `1` (number, required) - ID of the User in the form of an integer

+ Response 204 (application/json)

### Get Groups [GET /accounts/{id}/groups]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Group1)
            + (Group2)
        + message: {GROUP_COUNT} Group(s) retrieved successfully.

### Get Organizations [GET /accounts/{id}/groups]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Group1)
            + (Group2)
        + message: {GROUP_COUNT} Group(s) retrieved successfully.

### Get Nodes [GET /accounts/{id}/nodes]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Node)
            + (Node)
        + message: {ITEM_COUNT} Node(s) retrieved successfully.

### Get Expenses [GET /accounts/{id}/expenses]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Expense)
            + (Expense)
        + message: {ITEM_COUNT} Expense(s) retrieved successfully.

### Get Products [GET /accounts/{id}/products]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Product)
            + (Product)
        + message: {ITEM_COUNT} Product(s) retrieved successfully.

### Get Revenue [GET /accounts/{id}/revenue]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Revenue)
            + (Revenue)
        + message: {ITEM_COUNT} Revenue(s) retrieved successfully.

### Get Services [GET /accounts/{id}/services]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Service)
            + (Service)
        + message: {ITEM_COUNT} Service(s) retrieved successfully.