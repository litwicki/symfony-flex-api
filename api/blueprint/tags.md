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
        + message: {TAG_NAME} removed successfully.