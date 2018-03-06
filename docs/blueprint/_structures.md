FORMAT: 1A
HOST: http://api.myapp.dev/api/v1

# API

Private API Documentation.

# Data Structures

## RoleAdmin (object)
+ id: 1 (number)
+ status: 1 (number)
+ create_date: `2015-08-05T08:40:51.620Z` (string, required)
+ update_date: `2015-08-05T08:40:51.620Z` (string, required)
+ name: Administrator (string, required)
+ role: ROLE_ADMIN (string, required)
+ body: Administrators do what they want! (string, required)

## RoleUser (object)
+ id: 1 (number)
+ status: 1 (number)
+ create_date: `2015-08-05T08:40:51.620Z` (string, required)
+ update_date: `2015-08-05T08:40:51.620Z` (string, required)
+ name: User (string, required)
+ role: ROLE_USER (string, required)
+ body: Users do what they want! (string, required)

## RoleDeveloper (object)
+ id: 1 (number)
+ status: 1 (number)
+ create_date: `2015-08-05T08:40:51.620Z` (string, required)
+ update_date: `2015-08-05T08:40:51.620Z` (string, required)
+ name: Developer (string, required)
+ role: ROLE_DEVELOPER (string, required)
+ body: Developers do what they want! (string, required)

## User1 (object)
+ id: 1 (number)
+ status: 1 (number)
+ create_date: `2015-08-05T08:40:51.620Z` (string, required)
+ update_date: `2015-08-05T08:40:51.620Z` (string, required)
+ username: "optimus_prime" (string, required)
+ api_key: "" (string, required)
+ api_enabled: true (boolean, required)
+ signature: "Autobots, Assemble!"
+ last_online_date: `2015-08-05T08:40:51.620Z` (string, required)
+ person (Person1)
+ roles (array[AdminRole, DeveloperRole])

## User2 (object)
+ id: 2 (number)
+ status: 1 (number)
+ create_date: `2015-08-05T08:40:51.620Z` (string, required)
+ update_date: `2015-08-05T08:40:51.620Z` (string, required)
+ username: "megatron" (string, required)
+ api_key: "" (string, required)
+ api_enabled: true (boolean, required)
+ signature: "Kill All The Humans!"
+ last_online_date: `2015-08-05T08:40:51.620Z` (string, required)
+ person (Person2)
+ roles (array[DeveloperRole])

## User3 (object)
+ id: 3 (number)
+ status: 1 (number)
+ create_date: `2015-08-05T08:40:51.620Z` (string, required)
+ update_date: `2015-08-05T08:40:51.620Z` (string, required)
+ username: "bumblebee" (string, required)
+ api_key: "" (string, required)
+ api_enabled: true (boolean, required)
+ signature: "Go Autobots!!!"
+ last_online_date: `2015-08-05T08:40:51.620Z` (string, required)
+ person (Person3)
+ roles (array[UserRole])