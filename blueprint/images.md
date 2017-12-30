## Image [/images]

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
        + message: {IMAGE_NAME} removed successfully.