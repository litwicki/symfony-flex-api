## Comment [/comments]

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

+ Response 204 (application/json)