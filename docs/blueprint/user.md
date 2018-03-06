## User [/users]

### Forgot Password [POST /auth/forgot]

+ Request (application/json)

        {
            "email": "jake.litwicki@gmail.com"
        }
        
+ Response 200 (application/json)

        {
            message: "A reset password link was emailed to {EMAIL}",
            data: {
                "password_token": "3cd3ce85-b300-45bd-9469-b58f82f0ebdb",
                "password_token_expire": `2015-08-05T08:40:51.620Z`
            }
        }

### Reset Password [POST /auth/reset]

+ Request (application/json)

        {
            "password_token": "3cd3ce85-b300-45bd-9469-b58f82f0ebdb",
            "new_password": "Password1!",
            "new_password_confirm": "Password1!"
        }
        
+ Response 200 (application/json)

        {
            token: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.TJVA95OrM7E2cBab30RMHrHDcEfxjoYZgeFONFh7HgQ"
        }
   
### Signup (Create User) [POST /signup]

+ Request (application/json)

        {
            "username": "jakelitwicki",
            "roles": ["ROLE_ADMIN"],
            "first_name": "Jake",
            "last_name": "Litwicki",
            "email": "jake.litwicki@gmail.com",
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (User1)
        + message: {USERNAME} retrieved successfully.

### List Users [GET /users]

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (User1)
            + (User2)
        + message: {NUM} Users retrieved successfully.

### View User [GET /users/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the User in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (User1)
        + message: {USERNAME} retrieved successfully.

### Edit User [PATCH /users/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the User in the form of an integer

+ Request (application/json)

        {
            "type": "blog",
            "title": "My New User",
            "body": "<h1>How Cool</h1><p>HTML is allowed here!</p>"
            "type": "page"
        }

+ Response 200 (application/json)
    + Attributes
        + data (array) - Response data
            + (User1)
        + message: {USERNAME} retrieved successfully.

### Delete User [DELETE /users/{id}]

+ Parameters
    + id: `1` (number, required) - ID of the User in the form of an integer

+ Response 200 (application/json)
    + Attributes
        + message: {USERNAME} removed successfully.
