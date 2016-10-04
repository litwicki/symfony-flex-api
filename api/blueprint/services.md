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

