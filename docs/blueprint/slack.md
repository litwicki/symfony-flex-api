## Slack [/expenses]

### Submit a Slack Message [POST]

+ Request (application/json)

        {
            "text": "Tavro is awesome!",
            "channel": "#tavro",
            "username": "tavro-bot",
            "emoji": ":emoji_name:"
        }

+ Response 200 (application/json)
    + Attributes
        + message: "Slack message posted successfully!"