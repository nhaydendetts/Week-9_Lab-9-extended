# Week-9_Lab-9-extended
Week 9, Lab 9 extended

The index.php in this repository provides a GET interface that allows a user to Select All, Select One, Create, Update or Delete a contact in a contact database. (Note the the documentation assumes that "indext.php" will serve as the default document for the root folder of your PHP enabled server.

In order for this interface to work, the following constant values must be set up within the Index.php file

```php
$host="ADDRESS of DataBase"; #The host address of the DB 
$dbuser="root"; #The DB username
$dbpass="XXXX"; #The DB password
$db="DataBaseName"; #The DataBase to use
$table="TableName"; #The table name to use
```

If the DB or Table do not exist on the MySQL database provided by the $host variable, the code with set up the proper database and table as needed.

Once the database is set up and the index.php placed in a PHP enabled server folder, you can run the REST functions as detailed below:

****
# GET (default action) - Selects and returns all fields for all contacts in the database as JSON object

Call URL: curl localhost/index.php/username -X GET

PARAMETERS:

-- username: Expected Value: Username (optional "allusers" will get all users in the DB)  REQUIRED

RETURNS:

-- JSON object of all fields for all contacts in the database or error message


****
# POST - Creates new contact record based on provided: Username, Firstname, Lastname, Age, Email, and Zipcode. Returns a confirmation message of success or failure

Call URL: curl localhost/index.php/UserNAME/FirstName/Lastname/Age/Email/Zip -X POST

PARAMETERS:

-- UserName: Expected Value: Username for new user REQUIRED

-- email: Expected Value: email address of contact you are creating REQUIRED

-- fname: Expected Value: New contact first name REQUIRED

-- lname: Expected Value: New contact last name REQUIRED

-- age: Expected Value: New contact age (as numeric: i.e. 24) REQUIRED

-- zip: Expected Value: New contact zip code REQUIRED

RETURNS:
-- Confirmation JSON object or error message

****
# PUT - Updates a contact record based on provided: UserName, Email, Firstname, Lastname, Age, and Zipcode. Returns a confirmation message of success or failure

Call URL: curl localhost/index.php/UserNAME/FirstName/Lastname/Age/Email/Zip -X PUT 

PARAMETERS:

FOR ALL VALUES EXCEPT USERNAME, A VALUE OF "x" INDICATES THAT FIELD WILL NOT BE UPDATED

-- UserName: Expected Value: Username of new user REQUIRED

-- email: Expected Value: email address of contact you are updating REQUIRED

-- fname: Expected Value: New contact first name REQUIRED

-- lname: Expected Value: New contact last name REQUIRED

-- age: Expected Value: New contact age (as numeric: i.e. 24) REQUIRED

-- zip: Expected Value: New contact zip code REQUIRED

RETURNS:

-- Confirmation JSON object or error message

****
# DELETE - Removes a contact record based on provided: Username. Returns a confirmation message of success or failure

Call URL: curl localhost/restapi.php/UserName -X DELETE

PARAMETERS:

-- UserName: Expected Value: UserName of contact you are updating REQUIRED

RETURNS:

-- Confirmation JSON object or error message

