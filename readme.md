# PHP MVC Blank
The project is template for blank PHP project based on MVC architecture. 
The application is seperated to `controllers` (handling requests), `models` (handling database data) 
and `views` (rendering templates).

**Tags:** PHP, MVC

## Table of contents
1. [Motivation](#motivation)
2. [Configuration](#configuration)
3. [Getting Started](#getting-started)
	- [Database](#database)

## 1. Motivation
This project was created for purpose to easily create solution for simple MVC application.

## 2. Configuration
The application doesn't require any specia configuration besided `.env` file. You can create `env` file
manually or copy file `.env.example` to new file with name `.env`.

Example of `.env` file:
```
APP_NAME=MVC App
APP_URL=http://localhost

DB_HOST=
DB_USER=
DB_PASS=
DB_NAME=
```

## 3. Getting Started
### Database
First you will need define environment variables in file `.env` to ensure 
that connection to `MySQL` database can be done. Fill out `DB_HOST`, `DB_USER`, `DB_PASS` and `DB_NAME` variables:

```
DB_HOST=
DB_USER=
DB_PASS=
DB_NAME=
```

Then you can execute SQL queries using methods `fetchOne`, `fetchAll` or `execute`.
```php
<?php

use Lib\Application\DB;

...
$result = DB::fetchAll('SELECT * FROM users');
...
```

When you are using parameters that you pass to SQL query, you should replace values in SQL query with key `:name_of_column` and values then pass to second parameter, in which expects associative array of values with keys as name of columns.
```php
<?php

use Lib\Application\DB;

...
$result = DB::execute('INSERT INTO users(first_name, last_name, login, password) VALUES(:first_name, :last_name, :login, :password)', [
	'first_name' => $firstName,
	'last_name' => $lastName,
	'login' => $login',
	'password' => $password
]);
...
```