## Groups [/groups]

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

+ Response 204 (application/json)