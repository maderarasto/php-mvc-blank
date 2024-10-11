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
	- [Models](#models)

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

### Models
Models are entity objects that interact with database and each model corresponds to its database table. Models allow you easily interact with database 
using their methods such as finding records by their primary key, saving them with current state of their data or deleting them.

#### Creating model
##### Manually creating class
First you will need to create new class for your model. Each model should extends base model class `\Lib\Application\Model` and should be located in
`app/models` directory. Name of class should be in pascal case to easily resolve table name for its model.

```php
<?php

namespace App\Models;

use Lib\Application\Model;

class User extends Model
{
	...
}
```

##### Custom table name
If you have custom defined table names you can override name of table that corresponds to your model using `protected` property with type `string`.
If you define `$tableName` predefined query methods will use this name and will not resolve based on class name.

```php
<?php

namespace App\Models;

use Lib\Application\Model;

class User extends Model
{
	protected string $tableName = 'wp_users';
	...
}
```

##### Fill attributes of model
You can populate model attributes with associative array using static methods `create`, `update` or with method `fill` on instance.
These methods will set only attributes that are listed in `$fillable` field so you should override base model field with your columns:
```php
<?php

namespace App\Models;

use Lib\Application\Model;

class User extends Model
{
    protected string $fillable = [
        'fullname',
        'username',
        'email'
    ];
    ...
}
```

##### Hide attributes in model
If you don't want to populate some columns while querieng records from database, you can use `$hidden` field 
to list column names that you don't want to be populated if you use methods like `find`, `findMany` or `all`.
You could that by overriding this field like this:
```php
<?php

namespace App\Models;

use Lib\Application\Model;

class User extends Model
{
    protected string $hidden = [
        'password'
    ];
    ...
}
```