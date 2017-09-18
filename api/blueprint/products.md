## Product [/products]

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
        + message: {PRODUCT_NAME} removed successfully.