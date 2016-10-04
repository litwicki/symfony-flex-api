## Node [/nodes]

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
        
