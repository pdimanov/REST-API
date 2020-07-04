<h1>Basic REST API</h1>

A simple REST API that lets the user add new players and select the players

<h2>Setup</h2>
After you pull the repository, update the composer dependencies by executing the following command in the project folder:

`composer update`

After the dependencies are updated, Doctrine needs to be connected to a database.
One way to do that is to make a new file called `.env.local` in the project root directory.
Edit the file by entering the following line:

`DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
`

Parameters to edit:

`db_usesr`: enter your database user

`db_password`: enter your database password

`db_name`: enter the database name you're going to use

After ensuring the database is existent, execute the following command to create the player's entity table.

`php bin/console doctrine:migrations:migrate`

<h2>Starting the server</h2>
There are several ways to run the application in a browser, whether if it's Nginx, Apache or some other webserver. One of the fastest ways is to use Symfony's web server:
```
cd my-project
symfony server:start
```

If you open `http://127.0.0.1:8000/` in your browser, you should see Symfony's welcome page.

<h2>API Documentation</h2>
`domainName` is the domain name that you're using to open the application in your browser, e.g. if you're using Symfony's web server `domainName` would be `127.0.0.1:8000`.

- **Route**: `{domainName}/players/list`
    
    **Method**: `GET`
    
    **Parameter keys**: `page | size`
    
    **Parameter Values**: `page: integer (default value is 1) | size: integer (default value is 10)`

    **Parameter Example**: `{domainName}/players/list?page=2&size=3`
    
    **Response**: Returns a list of players with the right pagination

<br>

- **Route**: `{domainName}/players/list/{country}`
    
    **Method**: `GET`
    
    **Parameter keys**: `country | page | size`
    
    **Parameter Values**: `country: string (if misses, all the players will be returned) | page: integer (default value is 1) | size: integer (default value is 10)`

    **Parameter Example**: `{domainName}/players/list/bulgaria?page=2&size=3`
    
    **Response**: Returns a list of players from a specific country if the parameter is provided

<br>

- **Route**: `{domainName}/players/add`
    
    **Method**: `POST`
    
    **Body example**: 
    ```
        {
           "name": "Ivan Ivanov",
           "country": "Bulgaria",
           "birth_date": "1990-07-20"
        }
    ```
    
    **Response**: Returns a message if the player has been added successfully. If there is an error (Internal server error or a validation error) an error message is being returrned.


