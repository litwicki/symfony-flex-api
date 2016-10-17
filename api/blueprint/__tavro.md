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
            + (Group1)
            + (Group2)
        + message: {GROUP_COUNT} Group(s) retrieved successfully.

### Get Contacts [GET /accounts/{id}/contacts]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Contact1)
            + (Contact2)
        + message: {CONTACT_COUNT} Contact(s) retrieved successfully.

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
            + (Node1)
            + (Node2)
        + message: {ITEM_COUNT} Node(s) retrieved successfully.

### Get Expenses [GET /accounts/{id}/expenses]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Expense1)
            + (Expense2)
        + message: {ITEM_COUNT} Expense(s) retrieved successfully.

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
        + message: {ITEM_COUNT} Product(s) retrieved successfully.

### Get Revenue [GET /accounts/{id}/revenue]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Revenue1)
            + (Revenue2)
        + message: {ITEM_COUNT} Revenue(s) retrieved successfully.

### Get Services [GET /accounts/{id}/services]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Service1)
            + (Service2)
        + message: {ITEM_COUNT} Service(s) retrieved successfully.

### Get Shareholders [GET /accounts/{id}/shareholders]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Shareholder1)
            + (Shareholder2)
        + message: {ITEM_COUNT} Shareholder(s) retrieved successfully.## Comment [/comments]

Comments are created via `POST` to the object the comment is being submitted to.

### Edit Comment [PATCH /comments/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Comment in the form of an integer

+ Request (application/json)

        {
            "body": "This round of funding is exclusive to friends and family.",
            "status": 1
        }

+ Response 204 (application/json)

### Delete Comment [DELETE /comments/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Comment in the form of an integer

+ Response 204 (application/json)## Contact [/contacts]

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
    + message: {CONTACT_NAME} removed successfully.## Expense [/expenses]

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
        + message: {EXPENSE_NAME} removed successfully.## File [/files]

### Create new File [POST /files]

+ Request (multipart/form-data; boundary=---BOUNDARY)

        -----BOUNDARY
        Content-Disposition: form-data; name="files[file]"; filename="document.pdf"
        Content-Type: applicationpdf
        Content-Transfer-Encoding: base64

        /9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0a
        HBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIy
        MjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIA
        AhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEB
        AAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AL+AD//Z
        -----BOUNDARY

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (File1)
        + message: {FILE_COUNT} retrieved successfully.

### Edit File [PUT /files]

+ Request (multipart/form-data; boundary=---BOUNDARY)

        -----BOUNDARY
        Content-Disposition: form-data; name="files[file]"; filename="document.pdf"
        Content-Type: applicationpdf
        Content-Transfer-Encoding: base64

        /9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0a
        HBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIy
        MjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIA
        AhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEB
        AAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AL+AD//Z
        -----BOUNDARY

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (File1)
        + message: {FILE_COUNT} retrieved successfully.

### Get File [GET /files/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the File in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (File1)
        + message: {FILE_NAME} retrieved successfully.

### Delete File [DELETE /files/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the File in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {FILE_NAME} removed successfully.
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
        + message: {FUNDING_NAME} removed successfully.## Groups [/groups]

### Add Group to Account [POST /accounts/{id}/groups]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Request (application/json)

        {
            "body": "This is my new group",
            "user": 1
        }

+ Response 200 (application/json)

    + Attributes (AccountGroup1)

### Edit Group [PATCH /groups/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Group in the form of an integer

+ Request (application/json)

        {
            "name": "I can change the Group name",
            "user": 1
        }

+ Response 200 (application/json)

    + Attributes (AccountGroup1)

### Delete Group [DELETE /groups/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Group in the form of an integer

+ Response 204 (application/json)

### Get Users within Group [GET /groups/{id}/users]

+ Parameters
    + id: `1` (number, required) - ID of the Account in the form of an integer

+ Response 200 (application/json)

    + Attributes (array[User1, User2])

### Add User to Group [POST /groups/{id}/users]

+ Parameters
    + id: `1` (number, required) - ID of the Group in the form of an integer

+ Request (application/json)

        {
            "user": 1
        }

+ Response 200 (application/json)

    + Attributes (AccountGroup1)

### Remove User from Group [DELETE /groups/{group_id}/users/{user_id}]

+ Parameters
    + group_id: `1` (number, required) - ID of the Group in the form of an integer
    + user_id: `1` (number, required) - ID of the User in the form of an integer

+ Response 204 (application/json)## Image [/images]

`Image` entities are created via `POST` to a parent objects `/images` path.

### Get Image [GET /images/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Image in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Image1)
        + message: {IMAGE_NAME} retrieved successfully.

### Delete Image [DELETE /images/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Image in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {IMAGE_NAME} removed successfully.## Node [/nodes]

### View Node [GET /nodes/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Node in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Node1)
        + message: {NODE_NAME} retrieved successfully.

### Create Node [POST]

- `display_date` will be defaulted to `create_date` unless specified
- `display_date` cannot be a date in the past
- `type` will be defaulted to `node` unless specified

+ Request (application/json)

        {
            "title": "My new Node!",
            "body": "<h1>How cool</h1><p>HTML is allowed here!</p>",
            "user": 13,
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Node1)
        + message: {NODE_NAME} retrieved successfully.

### Edit Node [PATCH /nodes/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Node in the form of an integer

+ Request (application/json)

        {
            "type": "blog",
            "title": "My New Node",
            "body": "<h1>How Cool</h1><p>HTML is allowed here!</p>"
            "type": "page"
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Node1)
        + message: {NODE_NAME} retrieved successfully.

### Tag Node [POST /nodes/{id}/tags]

+ Parameters
    + id: `1` (number, required) - ID of the Node in the form of an integer

+ Request (application/json)

        {
            "tag": "dinosaur"
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Node1)
        + message: {NODE_NAME} retrieved successfully.

### Remove Tag [DELETE /nodes/{node_id}/tags/{tag_id}]

+ Parameters
    + node_id: `1` (number, required) - ID of the Node in the form of an integer
    + tag_id: `1` (number, required) - ID of the Tag in the form of an integer


+ Response 200 (application/json)
    + Attributes
        + message: {TAG_NAME} retrieved successfully.
        
### Delete Node [DELETE /nodes/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Node in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {NODE_NAME} removed successfully.      
             
### Create new Comment [POST /nodes/{id}/comments]

+ Parameters
    + id: `1` (number, required) - ID of the Node in the form of an integer

+ Request (application/json)

        {
            "user": 1,
            "body": "This is incredibly helpful"
        }
        
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
        + message: {ORG_NAME} removed successfully.## Product [/products]

### GET Product [GET /products/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Product in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Product1)
        + message: {PRODUCT_NAME} retrieved successfully.

### Create new Product [POST /products]

+ Request (application/json)

        {
            "title": "Product name",
            "body": "This product is amazing",
            "price": 79.99,
            "cost": 99.99,
            "category": 2
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Product1)
        + message: {PRODUCT_NAME} created successfully.

### Edit Product [PATCH /products/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Product in the form of an integer

+ Request (application/json)

        {
            "title": "Product Name",
            "body": "",
            "price": 79.99,
            "cost": 99.99,
            "category": 2,
            "status": 1,

        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Product1)
        + message: {PRODUCT_NAME} updated successfully.

### Tag [POST /products/{id}/tags]

+ Parameters
    + id: `1` (number, required) - ID of the Product in the form of an integer

+ Request (application/json)

        {
            "tag": "product tag"
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Product1)
        + message: {TAG_NAME} added successfully.

### Add Image [POST /products/{id}/images]

+ Parameters
    + id: `1` (number, required) - ID of the Product in the form of an integer

+ Request (multipart/form-data; boundary=---BOUNDARY)

        -----BOUNDARY
        Content-Disposition: form-data; name="image[file]"; filename="image.jpg"
        Content-Type: image/jpeg
        Content-Transfer-Encoding: base64

        /9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0a
        HBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIy
        MjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIA
        AhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEB
        AAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AL+AD//Z
        -----BOUNDARY

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Product1)
        + message: {IMAGE_NAME} added successfully.

### Delete Product [DELETE /products/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Product in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {PRODUCT_NAME} removed successfully.## Revenue [/revenue]

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

## Service [/services]

### View Service [GET /services/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Service in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Service1)
        + message: {SERVICE_NAME} retrieved successfully.

### Create new Service [POST /services]

+ Request (application/json)

        {
            "title": "Service name",
            "body": "Service description",
            "price": 24.99,
            "type": "service type",
            "category": 1,
            "organization": 1
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Service1)
        + message: {SERVICE_NAME} created successfully.

### Add Image [POST /services/{id}/images]

+ Parameters
    + id: `1` (number, required) - ID of the Service in the form of an integer

+ Request (multipart/form-data; boundary=---BOUNDARY)

        -----BOUNDARY
        Content-Disposition: form-data; name="image[file]"; filename="image.jpg"
        Content-Type: image/jpeg
        Content-Transfer-Encoding: base64

        /9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0a
        HBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIy
        MjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIA
        AhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEB
        AAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AL+AD//Z
        -----BOUNDARY

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Service1)
        + message: {IMAGE_NAME} added successfully.

### Edit Service [PATCH /services/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Service in the form of an integer

+ Request (application/json)

        {
            "body": "Revenue type",
            "organization": 1
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Service1)
        + message: {SERVICE_NAME} updated successfully.

### Delete Service [DELETE /services/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Service in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {SERVICE_NAME} removed successfully.

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
        + message: {SHAREHOLDER_NAME} removed successfully.## Syndicate [/syndicate]

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

## Tag [/tags]

### Edit Tag [PATCH /tags/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Tag in the form of an integer

+ Request (application/json)

        {
            "tag": "something",
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Tag1)
        + message: {TAG_NAME} updated successfully.

### Delete Tag [DELETE /tags/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Tag in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {TAG_NAME} removed successfully.## Variable [/variables]

### Get Variable [PATCH /variables/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the Variable in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (Variable1)
        + message: {VARIABLE_NAME} updated successfully.
        