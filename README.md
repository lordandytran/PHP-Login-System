# PHP-Login-System
Simple tokens based login system using PHP

Installation Requirements:
PostgreSQL database
PHP web server with PDO enabled


Instructions:
1. Use psql.txt to create database tables.
2. Modify 'scripts/db_connect.php' with database info.
3. For the secret code either generate a new one or leave it.
4. Put everything in your web root.
5. Include the 'redirect.php' on top of your index file or mainpage.


User Registration:
1. Use 'register.php' to register your user.
2. Hash your username (convert all to lowercase) using SHA256 to get the user id. Or use 'scripts/hashUser.php' with ?username= parameter. Example: hashUser.php?username=myuser
3. To verify your user use 'scripts/verify.php' to enter your user's id.


Logout:
To logout, just redirect the user to 'logout.php' to remove all tokens associated with and destroy the current sesion.

About:
The system salts and hashes the password using the Blowfish cipher with an algorithmic cost of 10.
Upon entering the correct user credentials, the system will generate an access and a refresh token.
The access token is used for the current session and is destroyed when your browser cookies are cleared or within one day.
The refresh token is saved to your browser local storage and reauthenticates you as long as your IP and User Agent remains the same.
The refresh token expires in 7 days or when your browser decides to clear it.