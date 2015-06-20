## Simple REST API using laravel

# Installation
This API uses laravel 4.2. 

Import DB schema and copy files in following locations in any standard laravel install:

````sh
StatesController.php -> /laravel/app/controllers/
UsersController.php -> /laravel/app/controllers/
routes.php -> /laravel/app/
Cities.php -> /laravel/app/models
Users.php -> /laravel/app/models
Visits.php -> /laravel/app/models
````

# Usage

API supports GET and POST operations.

** List all cities in a state:**

`GET /v1/states/{state}/cities`

Here {state} is 2 character state code such as TX, CA, IL, NY. A list of cities in given state is returned as a paginated result with 50 cities in 1 array. To access more data, use page parameter in the request. Such as `GET /v1/states/{state}/cities?page=2`.

Invalid code will result in error code 406 - Input not acceptable.

** List cities within a 100 mile radius of a city: ** 

`GET /v1/states/{state}/cities/{city}?radius=100`

Here {city} is the city ID that can be obtained from previous step. A list of nearby cities is returned as a paginated result with 50 cities in 1 array. To access more data, use page parameter in the request. Such as `GET /v1/states/{state}/cities/{city}?radius=100&page=2`.

If no radius is entered, cities in 100 miles are returned. Invalid city ID will result in error code 406 - Input not acceptable.

** Allow a user to update a row of data to indicate they have visited a particular city: **

`POST /v1/users/{user}/visits`

	```
	{
		"city": "Chicago",
		"state": "IL"
	}
	```

This creates a new entry in visits table or update if it already exists. A visit ID is returned to user upon successful operation.

** Return a list of cities the user has visited: **

`GET /v1/users/{user}/visits`

Here {user} is user ID from database. A list of cities that user has visited is returned upon successful operation.

## Bad requests
Any bad requests or incomplete requests return error code 400 - Bad request.

## Large data sets
For operations on states and cities, results are always paginated and can be accessed using page parameter in GET request.
