## Expense [/expenses]

### Get Expense [GET /expenses/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Expense in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Expense1)
        + message: {EXPENSE_NAME} retrieved successfully.

### Create Expense [POST]

+ Request (application/json)

        {
            "body": "Apiary for teams",
            "amount": 1.00
            "expense_date": "2009-02-03",
            "category": 3,
            "organization": 10
            "organization": 1
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Expense1)
        + message: {EXPENSE_NAME} created successfully.

### Delete Expense [DELETE /expenses/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Expense in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {EXPENSE_NAME} removed successfully.

### Tag [POST /expenses/{id}/tags]

+ Parameters
    + id: `1` (number, required) - ID of the Expense in the form of an integer

+ Request (application/json)

        {
            "tag": "lowercased"
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Expense1)
        + message: {EXPENSE_NAME} removed successfully.