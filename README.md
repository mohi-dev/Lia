## Installation

After cloning the project, run the following command in your terminal to install project dependencies:

```bash
composer install
```

Then you need to create```.env``` file To determine the environment variables, You can use the following command for this:

```bash
cp .example.env .env
```


Open the ```.env``` file and update the following variables according to your database

```
DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
⚠️ *Warning: Remember this project is compatible with MongoDB*

# Migrate the database
Once your credentials are in the .env file, now you can migrate your database.
```
php artisan migrate
```

# Seed the database
After the migrations are complete and you have the database structure required, then you can seed the database 

```
php artisan db:seed
```

# Cache
This project uses Redis for the cache mechanism. Therefore, it is necessary to set the following variables correctly:

```
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLIENT=predis
```

# Test
To make sure the project is working properly, you can run the tests with the following command:

```bash
php artisan test
```

## Usage
Enter the following command to run the project:


```bash
php artisan serve
```


## License

[MIT](https://choosealicense.com/licenses/mit/)