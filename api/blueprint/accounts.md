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

Virtually all entities are related to a particular `Account` and as such retrieving "all" records of a specific type funnels through the `account_id` in question.

### Get Groups [GET /accounts/{id}/groups]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (AccountGroup1)
            + (AccountGroup2)
        + message: {GROUP_COUNT} Groups retrieved successfully.

### Get Contacts [GET /accounts/{id}/contacts]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Contact1)
            + (Contact2)
        + message: {CONTACT_COUNT} Contacts retrieved successfully.

### Get Organizations [GET /accounts/{id}/groups]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (AccountGroup1)
            + (AccountGroup2)
        + message: {GROUP_COUNT} Groups retrieved successfully.

### Get Nodes [GET /accounts/{id}/nodes]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Node1)
            + (Node2)
        + message: {ITEM_COUNT} Nodes retrieved successfully.

### Get Expenses [GET /accounts/{id}/expenses]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Expense1)
            + (Expense2)
        + message: {ITEM_COUNT} Expenses retrieved successfully.

### Get Funding [GET /accounts/{id}/funding]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Funding1)
            + (Funding2)
        + message: {FUNDING_COUNT} Funding rounds retrieved successfully.

### Get Products [GET /accounts/{id}/products]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Product1)
            + (Product2)
        + message: {ITEM_COUNT} Products retrieved successfully.

### Get Revenue [GET /accounts/{id}/revenue]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Revenue1)
            + (Revenue2)
        + message: {ITEM_COUNT} Revenues retrieved successfully.

### Get Services [GET /accounts/{id}/services]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Service1)
            + (Service2)
        + message: {ITEM_COUNT} Services retrieved successfully.

### Get Shareholders [GET /accounts/{id}/shareholders]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Shareholder1)
            + (Shareholder2)
        + message: {ITEM_COUNT} Shareholders retrieved successfully.